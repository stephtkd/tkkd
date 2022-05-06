<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\MembershipRate;
use App\Entity\Order;
use App\Entity\OrderDetails;
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

    #[Route('/order_recap', name: 'order_recap', methods: "POST")]
    public function add(Cart $cart, Request $request): Response
    {
       /* $form = $this->createForm(OrderType::class,null, [
            'user' => $this->getUser()
        ]);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable();

            // enregistrer "la commande" dans Order()
            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setState(0);

            // statue de "la commande"
            // 0 non validée
            // 1 payée


            $this->entityManager->persist($order);

            // enregistrer les produits OrderDetails ()
            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);

            }

            $this->entityManager->flush();

            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFull(),
                'reference' => $order->getReference()
            ]);
        }

        return  $this->redirectToRoute('cart');*/
    }

}
