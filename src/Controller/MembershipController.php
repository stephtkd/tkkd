<?php

namespace App\Controller;

use App\Repository\MemberRepository;
use App\Repository\MembershipRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MembershipController extends AbstractController
{
    /**
     * allows admin to validate a affiliated after payment and document verification.
     *
     * @Route("/members/{id}/validate-affiliated-documents-and-paiement", requirements={"id": "\d+"}, name="validate-affiliated", methods={"POST"})
     * @ParamConverter("member", class="App:Member", options={"id": "id"})
     *
     * @param $member
     */
    public function validateMembershipDocumentsAndPaiement(MemberRepository $memberRepository, $member): JsonResponse
    {
        $member = $memberRepository->find($member);
        $membershipState = $member->getMembershipState();

        $entityManager = $this->getDoctrine()->getManager();

        if ('Validation en attente' == $membershipState) {
            $member->setMembershipState('Validée');
            $member->setUpToDateMembership(true);
            $entityManager->persist($member);
            $entityManager->flush();

            return $this->json($member);
        } elseif ('Paiement en attente' == $membershipState) {
            return $this->json(['erreur' => "le paiement de l'adhésion n'a pas été effectué"]);
        } else {
            return $this->json(['erreur' => "l'adhésion a déjà été validée"]);
        }
    }

    /**
     * allows to validate affiliated-paiement.
     *
     * @Route("/members/{id}/validate-affiliated-paiement", requirements={"id": "\d+"}, name="validate-affiliated-paiement", methods={"POST"})
     * @ParamConverter("member", class="App:Member", options={"id": "id"})
     *
     * @param $member
     */
    public function validateMembershipPaiement(MemberRepository $memberRepository, MembershipRepository $membershipRepository, $member): JsonResponse
    {
        $member = $memberRepository->find($member);
        $membershipState = $member->getMembershipState();

        $entityManager = $this->getDoctrine()->getManager();

        if ('Paiement en attente' == $membershipState) {
            $member->setMembershipState('Validation en attente');
            $entityManager->persist($member);
            $entityManager->flush();

            return $this->json($member);
        } elseif ('Validation en attente' == $membershipState) {
            return $this->json(['erreur' => 'le paiement a déjà été validé']);
        }
    }
}
