<?php

namespace App\Controller;

use App\Entity\EventSubscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscribeEventController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/subscribe_event', name: 'app_subscribe_event')] // page "suivi des évènements" dans le menu suivi du dashboard utilisateur
    public function index(): Response {
        $subscriptions = $this->entityManager->getRepository(EventSubscription::class)->findBy(['user' => $this->getUser()]);


        return $this->render('account/subscribeEvent.html.twig', [
            'subscriptions' => $subscriptions
        ]);
    }
}
