<?php

namespace App\Controller;

use App\Classe\affiliated;
use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private EventRepository $eventRepository;

    public function __construct(EntityManagerInterface $entityManager, EventRepository $eventRepository)
    {
        $this->entityManager = $entityManager;
        $this->eventRepository = $eventRepository;
    }

    /**
     * member file.
     *
     * @Route("/account/members", name="account_member") // affichage du recapitulatif du fomulaire d'inscription d'adhesion
     */
    public function index(): Response
    {
        $adhesion = $this->eventRepository->findValidAdhesions();

        return $this->render('account/member.html.twig', [
            'adhesion' => $adhesion
        ]);

    }

    #[Route('/account/members/form', name: 'account_member_add')] //affichage du formulaire d'adhesion, d'ajout de membre
    public function add(Request $request, affiliated $affiliated): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                $file = $form->get('photoName')->getData();
                $fileMedical = $form->get('medicalCertificateName')->getData();

                $member = $this->setFilesToMember($member, $file, $fileMedical);

                $member->setMembershipState('Paiement en attente');
                $member->setResponsibleAdult($this->getUser());

                $this->entityManager->persist($member);
                $this->entityManager->flush();

                $adhesion = $this->eventRepository->findValidAdhesions();

                if (is_null($adhesion)) {
                    return $this->redirectToRoute('account_member');
                }
                return $this->redirectToRoute('app_subscription', ['id' => $adhesion->getId()]);
        }

        return $this->render('account/memberForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/account/members-edit/{id}', name: 'account_member_edit')] //affichage du formulaire d'adhesion, de modification du membre
    public function edit(Request $request, $id): Response
    {
       $member = $this->entityManager->getRepository(Member::class)->findOneById($id);

        if(!$member || $member->getResponsibleAdult() != $this->getUser()) {
            return $this->redirectToRoute('account_member');
        }

        if (!is_null($member->getPhotoName())) {
            $member->setPhotoName('./upload/member/'.$member->getPhotoName());
        }
        if (!is_null($member->getMedicalCertificateName())) {
            $member->setMedicalCertificateName('./upload/member/' . $member->getMedicalCertificateName());
        }

        $form = $this->createForm(MemberType::class, $member);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('photoName')->getData();
            $fileMedical = $form->get('medicalCertificateName')->getData();

            $member = $this->setFilesToMember($member, $file, $fileMedical);

            $this->entityManager->persist($member);
            $this->entityManager->flush();
            return $this->redirectToRoute('account_member');
        }

        return $this->render('account/memberForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/account/members-delete/{id}', name: 'account_member_delete')] //suppression de membre
    public function delete($id): Response
    {
        $member = $this->entityManager->getRepository(Member::class)->findOneById($id);

        if($member && $member->getResponsibleAdult() == $this->getUser()) {
            $this->entityManager->remove($member);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('account_member');

    }

    private function setFilesToMember($member, $file, $fileMedical) {
        if (!is_null($file)) {
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('member_directory'),
                $fileName);
            $member->setPhotoName($fileName);
        } else if (!is_null($member->getPhotoName())) {
            $member->setPhotoName(str_replace("./upload/member/", "",$member->getPhotoName()));
        }
        if (!is_null($fileMedical)) {
            $fileNameMedical = md5(uniqid()) . '.' . $fileMedical->guessExtension();
            $fileMedical->move(
                $this->getParameter('member_directory'),
                $fileNameMedical);
            $member->setMedicalCertificateName($fileNameMedical);
        } else if (!is_null($member->getMedicalCertificateName())) {
            $member->setMedicalCertificateName(str_replace("./upload/member/", "", $member->getMedicalCertificateName()));
        }

        return $member;
    }

}
