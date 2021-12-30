<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\MemberRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * Get all events.
     *
     * @Route("/events", name="get-all-events", methods={"GET"})
     */
    public function getAllEvents(EventRepository $eventRepository): JsonResponse
    {
        $events = $eventRepository->findAll();
        if (empty($events)) {
            return $this->json(['message' => 'Aucun évènement'], Response::HTTP_OK, ['Content-Type', 'application/json']);
        }

        return $this->json($events, Response::HTTP_OK, ['Content-Type', 'application/json']);
    }

    /**
     * Find one event by its id.
     *
     * @Route("/events/{id}", requirements={"id": "\d+"}, name="get-event", methods={"GET"})
     *
     * @param $id
     */
    public function getEvent($id, EventRepository $eventRepository): JsonResponse
    {
        $event = $eventRepository->find($id);
        if (empty($event)) {
            return $this->json(['message' => 'Cet évènement n\'existe pas'], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        return $this->json($event, Response::HTTP_OK, ['Content-Type', 'application/json']);
    }

    /**
     * Add one event.
     *
     * @Route("/events/add", name="add-event", methods={"POST"})
     */
    public function addEvent(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $serializer = $this->get('serializer');
        $event = $serializer->deserialize($request->getContent(), Event::class, 'json');
        $errors = $validator->validate($event);
        if ($errors && count($errors)) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, ['Content-Type', 'application/json']);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($event);
        $entityManager->flush();

        return $this->json($event, Response::HTTP_CREATED, ['Content-Type', 'application/json']);
    }

    /**
     * Update event.
     *
     * @Route("/events/{id}/update", requirements={"id": "\d+"}, name="udpdate-event", methods={"PUT"})
     * @ParamConverter("id", class="App:Event", options={"id": "id"})
     *
     * @param $id
     */
    public function updateEvent($id, Request $request, EventRepository $eventRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $event = $eventRepository->find($id);

        if (empty($event)) {
            return $this->json(['message' => "Cet évènement n'existe pas"], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        $form = $this->createForm(EventType::class, $event);

        $data = json_decode($request->getContent(), true);

        $form->submit($data, false);

        if ($form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->json($event, Response::HTTP_OK, ['Content-Type', 'application/json']);
        } else {
            return $this->json($form, Response::HTTP_BAD_REQUEST, ['Content-Type', 'application/json']);
        }
    }

    /**
     * Delete event.
     *
     * @Route("/events/{id}/delete", requirements={"id": "\d+"}, name="delete-event", methods={"DELETE"})
     *
     * @param $id
     */
    public function deleteEvent($id, EntityManagerInterface $entityManager, EventRepository $eventRepository): JsonResponse
    {
        $event = $eventRepository->find($id);
        if (empty($event)) {
            return $this->json(['message' => 'Evenement inconnu'], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        $entityManager->remove($event);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT, ['Content-Type', 'application/json']);
    }

    /**
     * Add participant to one event.
     *
     * @Route("/events/{id}/add-participant/{memberId}", requirements={"id": "\d+"}, name="add-participant-to-event", methods={"PUT"})
     *
     * @param $id
     * @param $memberId
     */
    public function addParticipantToEvent($id, $memberId, EventRepository $eventRepository, MemberRepository $memberRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $event = $eventRepository->find($id);
        if (empty($event)) {
            return $this->json(['message' => "Cet évènement n'existe pas"], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        $member = $memberRepository->find($memberId);
        if (empty($member)) {
            return $this->json(['message' => "Cet adhérent n'existe pas"], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        $numberOfParticipants = count($eventParticipants = $event->getParticipants());
        $maximumNumberOfParticipants = $event->getMaximumNumberOfParticipants();
        if ($numberOfParticipants >= $maximumNumberOfParticipants) {
            return $this->json(['message' => "L'évènement est complet"], Response::HTTP_OK, ['Content-Type', 'application/json']);
        }

        $registrationDeadline = $event->getRegistrationDeadline();
        if ($registrationDeadline < new DateTime('NOW')) {
            return $this->json(['message' => 'les inscriptions pour cet évènement sont terminées'], Response::HTTP_OK, ['Content-Type', 'application/json']);
        }

        $participants = $event->getParticipants()->toArray();
        $alreadyRegisteredMember = in_array($member, $participants);
        if ($alreadyRegisteredMember) {
            return $this->json(['message' => "l'adhérent est déjà inscrit"], Response::HTTP_OK, ['Content-Type', 'application/json']);
        }

        $event->addParticipant($member);
        $entityManager->persist($event);
        $entityManager->flush();

        return $this->json($event, Response::HTTP_OK, ['Content-Type', 'application/json']);
    }

    /**
     * Remove participant from event.
     *
     * @Route("/events/{id}/remove-participant/{memberId}", requirements={"id": "\d+"}, name="add-participant-to-event", methods={"PUT"})
     *
     * @param $id
     * @param $memberId
     */
    public function removeParticipantFromEvent($id, $memberId, EventRepository $eventRepository, MemberRepository $memberRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $event = $eventRepository->find($id);
        if (empty($event)) {
            return $this->json(['message' => "Cet évènement n'existe pas"], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        $member = $memberRepository->find($memberId);
        if (empty($member)) {
            return $this->json(['message' => "Cet adhérent n'existe pas"], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        $participants = $event->getParticipants()->toArray();
        $alreadyRegisteredMember = in_array($member, $participants);
        if (!$alreadyRegisteredMember) {
            return $this->json(['message' => "l'adhérent n'est pas inscrit à cet évènement"], Response::HTTP_OK, ['Content-Type', 'application/json']);
        }

        $event->removeParticipant($member);
        $entityManager->persist($event);
        $entityManager->flush();

        return $this->json($event, Response::HTTP_OK, ['Content-Type', 'application/json']);
    }
}
