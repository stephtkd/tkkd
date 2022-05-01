<?php

namespace App\Controller;

use App\Form\HelloAssoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloAssoController extends AbstractController
{
    // Récupère les données du formulaire pour les passer au wrapper de l'api
   /* private $helloAssoApiService;

    public function __construct($helloAssoApiService)
    {
        $this->helloAssoApiService = $helloAssoApiService;
    }*/
    #[Route('order_details', name: 'app_order_details')]
    public function index(): Response
    {
        $form = new HelloAssoType();
        $form->id = rand();
        $form->method = 1;

        return $this->render('order_details/index.html.twig', [
            'controller_name' => 'HelloAssoController',
            'form' => $form,
        ]);
    }

    /**
     * Get all values posted from submitted form, store them in model then call api wrapper
     */
    public function post()
    {
        $form = new HelloAssoType();
        $form->id = $_POST['id'];
        $form->firstname = $_POST['firstname'];
        $form->lastname = $_POST['lastname'];
        $form->email = $_POST['email'];
        $form->amount = $_POST['amount'];
        $form->method = $_POST['method'];

        // Call API
        $response = $this->helloAssoApiService->initCart($form);

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
