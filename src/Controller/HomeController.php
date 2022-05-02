<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\HomeComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')] // page accueil
    public function index(): Response
    {
        $events= $this->entityManager->getRepository(Event::class)->findAll(); // affichage des cards evenements configurable dans l'easyAdmin
        $homeComment = $this->entityManager->getRepository(HomeComment::class)->findAll(); // affichage du texte configurable dans l'easyAdmin


        if (!$homeComment) {
            return $this->redirectToRoute('homeComment');
        }

        return $this->render('home/index.html.twig', [
            'events' => $events,
            'homeComment' => $homeComment
        ]);
    }
}
