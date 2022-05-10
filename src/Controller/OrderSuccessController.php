<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/order/success/{helloAssoSessionId}', name: 'app_order_success')]
    public function index(Cart $cart,$helloAssoSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByHelloSessionId($helloAssoSessionId);

        if(!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if ($order->getIsPaid () == 0) {
            $cart->remove();

            $order->setIsPaid(1);
            // statue du paiement
            // 0 non validée
            // 1 payée
            $this->entityManager->flush();

            // envoyer un email à notre client pour lui confirmer son paiement
          //  $mail = new Mail();
          //  $content = "Bonjour ".$order->getUser()->getFirstname()."<br/>Merci pour votre adhésion au club Taekwonkido Phenix<br/><br/>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.";
          //  $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre paiement est passer sur le site de Taekwonkido Phenix.', $content);
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }

}
