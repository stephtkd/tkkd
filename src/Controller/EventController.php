<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/event/{slug}', name: 'app_event')]
    public function show($slug): Response
    {
        {
            $event = $this->entityManager->getRepository(Event::class)->findOneBySlug($slug);


            if (!$event) {
                return $this->redirectToRoute('events');
            }

            return $this->render('event/index.html.twig', [
                'event' => $event,
            ]);

        }
    }

}
