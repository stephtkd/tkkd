<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/order/cancel/{helloAssoSessionId}', name: 'app_order_cancel')]
    public function index($helloAssoSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByHelloSessionId($helloAssoSessionId);

        if(!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        // envoyer un email à notre client pour lui indiquer l'échec du paiement
        //  $mail = new Mail();
        //  $content = "Bonjour ".$order->getUser()->getFirstname()."<br/>Un problème est survenu lors de votre adhésion au club Taekwonkido Phenix<br/><br/>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.";
        //  $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre paiement n'est pas passer sur le site de Taekwonkido Phenix.', $content);

        return $this->render('order_cancel/index.html.twig', [
            'order' => $order
        ]);
    }
}
