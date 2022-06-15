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
use Shuchkin\SimpleXLSX;

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
        $adhesion = $this->eventRepository->findActualAdhesion();
        
        return $this->render('account/member.html.twig', [
            'adhesion' => $adhesion
        ]);

    }

    #[Route('/account/members/import-csv', name: 'account_member_import_csv')] 
    public function importCsv(): Response
    {
        $adhesion = $this->eventRepository->findActualAdhesion();

        if ( $xlsx = SimpleXLSX::parse('test.xlsx') ) {
 
            foreach( $xlsx->rows() as $key=> $r ) {
                // $produit= new Produit();
                // $produit->setRef($r[0]);
                // $produit->SetPrix($r[1]);
               
                // $em->persist($produit);
 
            }
            // $em->flush();
 
        } else {
            echo SimpleXLSX::parseError();
        }

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

                $member = $this->setFilesToMember($member, $file);

                $member->setMembershipState('Paiement en attente');
                $member->setResponsibleAdult($this->getUser());

                $this->entityManager->persist($member);
                $this->entityManager->flush();

                $adhesion = $this->eventRepository->findActualAdhesion();

                if (is_null($adhesion)) {
                    return $this->redirectToRoute('account_member');
                }
                return $this->redirectToRoute('app_subscription', ['id' => $adhesion->getId()]);
        }

        return $this->render('account/memberForm.html.twig', [
            'form' => $form->createView(),
            'user' => $this->getUser()
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

        $form = $this->createForm(MemberType::class, $member);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('photoName')->getData();

            $member = $this->setFilesToMember($member, $file);

            $this->entityManager->persist($member);
            $this->entityManager->flush();
            return $this->redirectToRoute('account_member');
        }

        return $this->render('account/memberForm.html.twig', [
            'form' => $form->createView(),
            'user' => $member
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

    private function setFilesToMember($member, $file) {
        if (!is_null($file)) {
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('member_directory'),
                $fileName);
            $member->setPhotoName($fileName);
        } else if (!is_null($member->getPhotoName())) {
            $member->setPhotoName(str_replace("./upload/member/", "",$member->getPhotoName()));
        }

        return $member;
    }

}
