<?php

namespace App\Controller;

use App\Service\HelloAssoApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderDetailController  extends AbstractController
{
    private HelloAssoApiService $apiService;

    public function __construct(HelloAssoApiService $apiService) {
        $this->apiService = $apiService;
    }

    /**
     * @Route("/order_details", name="order_details") // détails de la commande
     */
    public function index(): Response
    {
        return $this->render('order_detail.html.twig');
    }


}