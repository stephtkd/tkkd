<?php

namespace App\Controller;

use App\Classe\affiliated;
use App\Entity\Member;
use App\Form\MemberType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * member file.
     *
     * @Route("/account/members", name="account_member") // affichage du recapitulatif du fomulaire d'inscription d'adhesion
     */
    public function index(): Response
    {
        return $this->render('account/member.html.twig');

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
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $fileNameMedical = md5(uniqid()) . '.' . $fileMedical->guessExtension();
                $file->move(
                        $this->getParameter('member_directory'),
                        $fileName);
                $fileMedical->move(
                    $this->getParameter('member_directory'),
                    $fileNameMedical);
                $member->setPhotoName($fileName);
                $member->setMedicalCertificateName($fileNameMedical);

                $member->setUpToDateMembership(0);
                $member->setResponsibleAdult($this->getUser());

                $this->entityManager->persist($member);
                $this->entityManager->flush();

                return $this->redirectToRoute('account_member');


        }

        return $this->render('account/memberForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/account/members-edit/{id}', name: 'account_member_edit')] //affichage du formulaire d'adhesion, de modification du membre
    public function edit(Request $request, $id): Response
    {
       $member = $this->entityManager->getRepository(Member::class)->findOneById($id);

        if(!$member || $member->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_member');
        }

        $form = $this->createForm(MemberType::class, $member);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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

        if($member && $member->getUser() == $this->getUser()) {

            $this->entityManager->remove($member);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('account_member');

    }


}
