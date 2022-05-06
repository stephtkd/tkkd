<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\MembershipRate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/subscription', name: 'app_subscription')]

    public function index(Cart $cart): Response
    {
        $memberShipRate= $this->entityManager->getRepository(MembershipRate::class)->findAll(); // affichage des tarifs configurable dans l'easyAdmin

        return $this->render('subscription/index.html.twig', [
            'memberShipRates' => $memberShipRate,
            'cart' => $cart->getFull()
        ]);
    }


}
