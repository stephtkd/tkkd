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

    #[Route('/subscription/add/{id}', name: 'add_subscription')]

    public function add(Cart $cart, $id): Response
    {
        $cart->add($id);

        return $this->redirectToRoute('app_subscription');
    }

    #[Route('/subscription/remove', name: 'remove_my_subscription')]

    public function remove(Cart $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('memberShipRates');
    }

    #[Route('/subscription/decrease/{id}', name: 'decrease_subscription')]

    public function decrease(Cart $cart, $id): Response
    {
        $cart->decrease($id);

        return $this->redirectToRoute('cart');
    }
}
