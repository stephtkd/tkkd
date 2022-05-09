<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\MembershipRate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/subscription', name: 'app_subscription')]  // order

    public function index(Cart $cart): Response
    {
        $memberShipRate= $this->entityManager->getRepository(MembershipRate::class)->findAll(); // affichage des tarifs configurable dans l'easyAdmin

        return $this->render('subscription/index.html.twig', [
            'memberShipRates' => $memberShipRate,
            'cart' => $cart->getFull()
        ]);
    }

    #[Route('/order_recap', name: 'order_recap')]
    public function add(Cart $cart, Request $request): Response
    {


            return $this->render('order/index.html.twig', [
                'cart' => $cart->getFull(),

            ]);

    }

}
