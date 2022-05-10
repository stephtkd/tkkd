<?php

namespace App\Controller;

use App\Classe\Cart;
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

    #[Route('/event/{slug}', name: 'app_event')] // page "presentationde l'evenement" => show de l'evenement de l'accueil
    public function show($slug, Cart $cart): Response
    {
        {
            $event = $this->entityManager->getRepository(Event::class)->findOneBySlug($slug);

            return $this->render('event/index.html.twig', [
                'event' => $event,
                'cart' => $cart->getFull()
            ]);

        }
    }

}
