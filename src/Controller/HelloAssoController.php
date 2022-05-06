<?php

namespace App\Controller;

use App\Form\HelloAssoType;

use App\Service\HelloAssoApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloAssoController extends AbstractController
{

    // Récupère les données du formulaire pour les passer au wrapper de l'api

    private HelloAssoApiService $apiService;

    public function __construct(HelloAssoApiService $apiService) {
        $this->apiService = $apiService;
    }

    #[Route('order_details', name: 'app_order_details')]

    public function index(): Response
    {
        $form = $this->createForm(HelloAssoType::class);

        return $this->render('order_details/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Get all values posted from submitted form, store them in model then call api wrapper
     */
    public function post() {
        $form = new HelloAssoType();

        $response = $this->apiService->initCart($form);

        if(isset($response->redirectUrl)) {
            // We can store checkout id somewhere
            //$response->checkoutIntentId;

            // then redirect to HelloAsso
            header('Location:' . $response->redirectUrl);
            exit();
        } else if (isset($response)) {
            $form->error = $response->error;
            return ['form', $form];
        } else {
            $form->error = "Une erreur inconnue s'est produite";
            return ['form', $form];
        }
    }

    public function return()
    {
        return ['return', null];
    }

}
