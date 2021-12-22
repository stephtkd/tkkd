<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Member;
use App\Entity\Membership;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use App\Repository\MembershipRateRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\DateTimeHandler;



class MemberController extends AbstractController
{

    /**
     * Get all members
     * @Route("/members", name="get-all-members", methods={"GET"})
     * @param MemberRepository $memberRepository
     * @return JsonResponse
     */
    public function getAllMembers(MemberRepository $memberRepository): JsonResponse
    {
        $members = $memberRepository->findAll();
        if (empty($members)) {
            return $this->json(['message' => 'Aucun Membre'], Response::HTTP_OK, ['Content-Type', 'application/json']);
        }

        return $this->json($members, Response::HTTP_OK, ['Content-Type', 'application/json']);
    }

    /**
     * Get one member
     * @Route("/members/{id}", requirements={"id": "\d+"}, name="get-one-member-by-id", methods={"GET"})
     * @param $id
     * @param MemberRepository $memberRepository
     * @return JsonResponse
     */
    public function getMemberById($id, MemberRepository $memberRepository): JsonResponse
    {
        $member = $memberRepository->find($id);
        if (empty($member)) {
            return $this->json(['message' => 'Ce membre n\'existe pas'], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        return $this->json($member, Response::HTTP_OK, ['Content-Type', 'application/json']);
    }

    /**
     * Add one member
     * @Route("/members/add", name="add-member", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param MembershipRateRepository $membershipRateRepository
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function addMember(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, MembershipRateRepository $membershipRateRepository, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);

        $member = new Member();
        $member->setLastName($data['lastName']);
        $member->setFirstName($data['firstName']);
        $member->setSex($data['sex']);
        $member->setComment($data['comment']);
        $member->setUpToDateMembership(false);
        $member->setLevel($data['level']);
        $member->setPhoneNumber($data['phoneNumber']);
        $member->setStreetAdress($data['streetAdress']);
        $member->setCity($data['city']);
        $member->setPostalCode($data['postalCode']);
        $member->setEmail($data['email']);
        $member->setNationality($data['nationality']);
        $member->setEmergencyPhone($data['emergencyPhone']);
        $member->setStatus("Élève");

        // TODO: add user connected as responsible adult
        $user = $userRepository->find($data['responsibleAdult']);
        $member->setResponsibleAdult($user);

        // Handle birthdate format
        try {
            $birthdate = new DateTime($data['birthdate']);
        } catch (Exception $exception) {
            $errors = ["type" => "https:\/\/tools.ietf.org\/html\/rfc2616#section-10", "title" => "An error occurred", "detail" => "birthdate: This value is not a valid birthdate.", "violations" => ["propertyPath" => "birthdate", "message" => "This value is not a valid birthdate."]];
            return $this->json($errors);
        }

        $birthdateString = date('Y-m-d', strtotime($data['birthdate']));
        $birthdateDateTimeFormat = DateTime::createFromFormat("Y-m-d", $birthdateString);
        $member->setBirthdate($birthdateDateTimeFormat);

        $membership = new Membership();
        $membership->setMembershipState("Paiement en attente");
        $todaysYear = date("Y");
        $membership->setSeasonYear($todaysYear . " - " . ($todaysYear + 1));
        // Handle membership rate
        $membershipRate = $membershipRateRepository->find($data['membershipRate']);
        $memberAge = DateTimeHandler::ageCalculation($birthdateString);
        $maximumAge = $membershipRate->getMaximumAge();
        if ($maximumAge && $memberAge > $maximumAge) {
            return $this->json(["Erreur" => "Le tarif selectionné est reservé aux enfants de $maximumAge ans maximum"], Response::HTTP_BAD_REQUEST,  ['Content-Type', 'application/json']);
        }
        else {
            $membership->setMembershipRate($membershipRate);
        }

        $member->addMembership($membership);

        $errors = $validator->validate($member);
        if ($errors && count($errors)) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST,  ['Content-Type', 'application/json']);
        }

        $entityManager->persist($member);
        $entityManager->flush();
        return $this->json($member, Response::HTTP_CREATED,  ['Content-Type', 'application/json']);

    }

    /**
     * Delete member
     * @Route("/members/{id}/delete", requirements={"id": "\d+"}, name="delete-member", methods={"DELETE"})
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @param MemberRepository $memberRepository
     * @return JsonResponse
     */
    public function deleteMember($id, EntityManagerInterface $entityManager, MemberRepository $memberRepository): JsonResponse
    {

        $member = $memberRepository->find($id);
        if (empty($member)) {
            return $this->json(['message' => "Au membre correspondant"], Response::HTTP_BAD_REQUEST, ['Content-Type', 'application/json']);
        }
        $entityManager->remove($member);
        $entityManager->flush();
        return $this->json("l'adhérent a bien été supprimé", Response::HTTP_NO_CONTENT, ['Content-Type', 'application/json']);

    }

    /**
     * Update member
     * @Route("/members/{id}/update", requirements={"id": "\d+"}, name="update-member", methods={"PUT"})
     * @ParamConverter("member", class="App:Member", options={"id": "id"})
     * @param $member
     * @param Request $request
     * @param MembershipRateRepository $membershipRateRepository
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @throws Exception
     */
    public function updateMember($member, Request $request, MembershipRateRepository $membershipRateRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        /** @var Member $member */
        $member->setFirstName($data['firstName']);
        $member->setLastName($data['lastName']);
        $membershipRateId = $data['membershipRate'];
        $membershipRate = $membershipRateRepository->find($membershipRateId);
        $birthdateString = date('Y-m-d', strtotime($data['birthdate']));
        $memberAge = DateTimeHandler::ageCalculation($birthdateString);
        $maximumAge = $membershipRate->getMaximumAge();
        if ($maximumAge && $memberAge > $maximumAge) {
            return $this->json(["Erreur" => "Le tarif selectionné est reservé aux enfants de $maximumAge ans maximum"], Response::HTTP_BAD_REQUEST,  ['Content-Type', 'application/json']);
        }
        $member->setMembershipRate($membershipRate);
        $member->setEmergencyPhone($data['emergencyPhone']);
        $member->setNationality($data['nationality']);
        $member->setStreetAdress($data['streetAdress']);
        $member->setPostalCode($data['postalCode']);
        $member->setCity($data['city']);
        $member->setPhoneNumber($data['phoneNumber']);
        $member->setLevel($data['level']);
        $member->setComment($data['comment']);
        $birthdate = new DateTime($data['birthdate']);
        $member->setBirthdate($birthdate);
        $member->setSex($data['sex']);
        $member->setEmail($data['email']);
        $member->setMedicalCertificateName($data['medicalCertificateName']);
        $member->setPhotoName($data['photoName']);

        $entityManager->persist($member);
        $entityManager->flush();
        return $this->json($member,Response::HTTP_OK, ['Content-Type', 'application/json']);
    }

    /**
     * Allows admin to update member
     * @Route("/admin/members/{id}/update", requirements={"id": "\d+"}, name="admin-update-one-member-by-id", methods={"PUT"})
     * @ParamConverter("member", class="App:Member", options={"id": "id"})
     * @param $id
     * @param Request $request
     * @param MemberRepository $memberRepository
     * @param MembershipRateRepository $membershipRateRepository
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @throws Exception
     */
    public function adminUpdateMember($id, Request $request, MemberRepository $memberRepository, MembershipRateRepository $membershipRateRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $member = $memberRepository->find($id);

        if (empty($member)) {
            return $this->json(['message' => "Pas de membre correspondant"], Response::HTTP_BAD_REQUEST, ['Content-Type', 'application/json']);
        }

        $form = $this->createForm(MemberType::class, $member);

        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $entityManager->persist($member);
            $entityManager->flush();
            return $this->json($member, Response::HTTP_OK, ['Content-Type', 'application/json']);
        }
        else {
            return $this->json($form, Response::HTTP_BAD_REQUEST, ['Content-Type', 'application/json']);
        }
    }

    /**
     * Get User (Responsible Adult) linked to one member
     * @Route("/members/{id}/get-user", requirements={"id": "\d+"}, name="get-responsible-adult", methods={"GET"})
     * @ParamConverter("member", class="App:Member", options={"id": "id"})
     * @param $member
     * @param MemberRepository $memberRepository
     * @return JsonResponse
     */
    public function getResponsibleAdult($member, MemberRepository $memberRepository): JsonResponse
    {
        $member = $memberRepository->find($member);
        if (empty($member)) {
            return $this->json(['message' => 'Aucun membre correspondant'], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }
        $userResponsible = $member->getResponsibleAdult();
        return $this->json($userResponsible, Response::HTTP_OK, ['Content-Type', 'application/json']);
    }

    /**
     * Get Members with finished membership
     * @Route("/members/finished-membership", name="get-members-with-finished-membership", methods={"GET"})
     * @param MemberRepository $memberRepository
     * @return JsonResponse
     */
    public function getMembersWithFinishedMembership(MemberRepository $memberRepository): JsonResponse
    {
        $membersWithFinishedMembership = $memberRepository->findBy(['membershipState' => "Terminée"]);

        if (empty($membersWithFinishedMembership)) {
            return $this->json(['message' => "Pas de membres à l'adhésion terminée"], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }
            return $this->json($membersWithFinishedMembership, Response::HTTP_OK, ['Content-Type', 'application/json']);
    }

    /**
     * Update Membershipstate
     * @param MemberRepository $memberRepository
     * @param ObjectManager $entityManager
     */
    public static function updateMembersMembershipState(MemberRepository $memberRepository, ObjectManager $entityManager)
    {
    }

}
