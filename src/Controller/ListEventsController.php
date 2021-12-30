<?php

namespace App\Controller;

use App\Entity\EventPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListEventsController extends AbstractController
{
    #[Route('/list', name: 'list', methods: 'GET')]
    public function list(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $listEventsRepository = $em->getRepository(EventPage::class);
        $listEvents = $listEventsRepository->orderingEvents();

        return $this->render('list_events/index.html.twig', [
            'listEvents' => $listEvents,
        ]);
    }
}
