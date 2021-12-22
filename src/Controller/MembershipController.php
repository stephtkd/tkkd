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
     * allows admin to validate a membership after payment and document verification
     * @Route("/members/{id}/validate-membership-documents-and-paiement", requirements={"id": "\d+"}, name="validate-membership", methods={"POST"})
     * @ParamConverter("member", class="App:Member", options={"id": "id"})
     * @param $member
     * @param MemberRepository $memberRepository
     * @return JsonResponse
     */
    public function validateMembershipDocumentsAndPaiement(MemberRepository $memberRepository, $member): JsonResponse
    {
        $member = $memberRepository->find($member);
        $membershipState = $member->getMembershipState();

        $entityManager = $this->getDoctrine()->getManager();

        if ($membershipState == "Validation en attente") {
            $member->setMembershipState("Validée");
            $member->setUpToDateMembership(true);
            $entityManager->persist($member);
            $entityManager->flush();
            return $this->json($member);
        }
        elseif ($membershipState == "Paiement en attente") {
            return $this->json(['erreur' => "le paiement de l'adhésion n'a pas été effectué"]);
        }
        else  {
            return $this->json(['erreur' => "l'adhésion a déjà été validée"]);
        }
    }

    /**
     * allows to validate membership-paiement
     * @Route("/members/{id}/validate-membership-paiement", requirements={"id": "\d+"}, name="validate-membership-paiement", methods={"POST"})
     * @ParamConverter("member", class="App:Member", options={"id": "id"})
     * @param MemberRepository $memberRepository
     * @param MembershipRepository $membershipRepository
     * @param $member
     * @return JsonResponse
     */
    public function validateMembershipPaiement(MemberRepository $memberRepository, MembershipRepository $membershipRepository, $member): JsonResponse
    {
        $member = $memberRepository->find($member);
        $membershipState = $member->getMembershipState();

        $entityManager = $this->getDoctrine()->getManager();

        if ($membershipState == "Paiement en attente") {
            $member->setMembershipState("Validation en attente");
            $entityManager->persist($member);
            $entityManager->flush();
            return $this->json($member);
        } elseif ($membershipState == "Validation en attente") {
            return $this->json(['erreur' => "le paiement a déjà été validé"]);
        }
        else {
            return $this->json(["error"=>"Le paiement n'a pas été effectué"]);
        }

    }
}
