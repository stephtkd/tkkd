<?php

namespace App\Controller;

use App\Form\HelloAssoType;

use App\Service\HelloAssoApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloAssoController extends AbstractController
{

    private HelloAssoApiService $apiService;

    public function __construct(HelloAssoApiService $apiService) {
        $this->apiService = $apiService;
    }

    #[Route('order_details', name: 'app_order_details')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(HelloAssoType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->apiService->generateCheckoutLink($form);

            return $this->redirect($response['redirectUrl']);
        }

        return $this->render('order_details/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function return()
    {
        return ['return', null];
    }

}
