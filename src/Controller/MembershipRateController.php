<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\MembershipRate;
use App\Form\MembershipRateType;
use App\Repository\MembershipRateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MembershipRateController extends AbstractController
{
    /**
     * Get all membership rates.
     *
     * @Route("/membership-rates", name="get-all-membership-rates", methods={"GET"})
     */
    public function getAllMembershipRates(MembershipRateRepository $membershipRateRepository): JsonResponse
    {
        $membershipRates = $membershipRateRepository->findAll();
        if (empty($membershipRates)) {
            return $this->json(['message' => 'Aucun tarif saisi'], Response::HTTP_OK, ['Content-Type', 'application/json']);
        }

        return $this->json($membershipRates, Response::HTTP_OK, ['Content-Type', 'application/json']);
    }

    /**
     * Get one membership rate.
     *
     * @Route("/membership-rates/{id}", requirements={"id": "\d+"}, name="get-one-membership-rate", methods={"GET"})
     * @ParamConverter("membershipRate", class="App:MembershipRate")
     *
     * @param $id
     */
    public function getMembershipRate($id, MembershipRateRepository $membershipRateRepository): JsonResponse
    {
        $membershipRate = $membershipRateRepository->find($id);
        if (empty($membershipRate)) {
            return $this->json(['message' => 'Ce tarif n\'existe pas'], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        return $this->json($membershipRate, Response::HTTP_OK, ['Content-Type', 'application/json']);
    }

    /**
     * Add one membership Rate.
     *
     * @Route("/membership-rates/add", name="add-membership-rate", methods={"POST"})
     */
    public function addMemberShipRate(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $membershipRate = new MembershipRate();

        $data = json_decode($request->getContent(), true);

        $membershipRate->setLabel($data['label']);
        $membershipRate->setPrice($data['price']);

        if (array_key_exists('maximumAge', $data)) {
            $membershipRate->setMaximumAge($data['maximumAge']);
        }

        $errors = $validator->validate($membershipRate);
        if ($errors && count($errors)) {
            return new JsonResponse($errors);
        }

        $entityManager->persist($membershipRate);
        $entityManager->flush();

        return $this->json($membershipRate);
    }

    /**
     * Delete one membership rate by its id.
     *
     * @Route("/membership-rates/{id}/delete/", requirements={"id": "\d+"}, name="delete-one-membership-rate-by-id", methods={"DELETE"})
     * @ParamConverter("membershipRate", class="App:MembershipRate", options={"id": "id"})
     *
     * @param $id
     */
    public function deleteMembershipRate($id, EntityManagerInterface $entityManager, MembershipRateRepository $membershipRateRepository): JsonResponse
    {
        $membershipRate = $membershipRateRepository->find($id);
        if (empty($membershipRate)) {
            return $this->json(['message' => 'Tarif inconnu'], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        $entityManager->remove($membershipRate);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT, ['Content-Type', 'application/json']);
    }

    /**
     * Update one membership rate.
     *
     * @Route("/membership-rates/update/{id}", requirements={"id": "\d+"}, name="udpdate-one-membership-rate-by-id", methods={"PUT"})
     * @ParamConverter("membershipRate", class="App:MembershipRate", options={"id": "id"})
     *
     * @param $id
     */
    public function updateMembershipRate($id, Request $request, MembershipRateRepository $membershipRateRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $membershipRate = $membershipRateRepository->find($id);
        if (empty($membershipRate)) {
            return $this->json(['message' => 'Tarif inconnu'], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        $form = $this->createForm(MembershipRateType::class, $membershipRate);
        $data = json_decode($request->getContent(), true);

        $form->submit($data, false);

        if ($form->isValid()) {
            $entityManager->persist($membershipRate);
            $entityManager->flush();

            return $this->json($membershipRate, Response::HTTP_OK, ['Content-Type', 'application/json']);
        } else {
            return $this->json($form, Response::HTTP_BAD_REQUEST, ['Content-Type', 'application/json']);
        }
    }

    /**
     * Get members by Membership Rate.
     *
     * @Route("/membership-rates/members/{id}", requirements={"id": "\d+"}, name="get-members-by-membership-rate", methods={"GET"})
     * @ParamConverter("membershipRate", class="App:MembershipRate", options={"id": "id"})
     *
     * @param $membershipRate
     */
    public function getMembersByMembershipRate($membershipRate, MembershipRateRepository $membershipRateRepository): JsonResponse
    {
        $members = $membershipRate->getMembers();

        return $this->json($members);
    }
}
