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

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $events= $this->entityManager->getRepository(Event::class)->findAll();
        $homeComment = $this->entityManager->getRepository(HomeComment::class)->findAll();


        if (!$homeComment) {
            return $this->redirectToRoute('homeComment');
        }

        return $this->render('home/index.html.twig', [
            'events' => $events,
            'homeComment' => $homeComment
        ]);
    }
}
