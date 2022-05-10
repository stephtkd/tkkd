<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\MembershipRate;
use App\Form\OrderType;
use App\Service\HelloAssoApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private HelloAssoApiService $apiService;

    public function __construct(EntityManagerInterface $entityManager, HelloAssoApiService $apiService)
    {
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
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
    public function checkout(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->apiService->generateCheckoutLink($form->getData());

            return $this->redirect($response['redirectUrl']);
        }

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull(),
        ]);
    }
}
