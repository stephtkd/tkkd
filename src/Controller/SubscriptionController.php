<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Repository\EventRepository;
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
    private EventRepository $eventRepository;

    public function __construct(EntityManagerInterface $entityManager, HelloAssoApiService $apiService, EventRepository $eventRepository)
    {
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
        $this->eventRepository = $eventRepository;
    }

    #[Route('/subscription/{id}', name: 'app_subscription')]  // order
    public function index($id): Response
    {
        $event = $this->eventRepository->findOneBy(['id' => $id]);

        return $this->render('subscription/index.html.twig', [
            'event' => $event
        ]);
    }

    #[Route('/order_recap', name: 'order_recap')]
    public function checkout(Request $request): Response
    {
        $errorMessage = "";
        $form = $this->createForm(OrderType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->apiService->generateCheckoutLink($form->getData());

            if ($response->getStatusCode() == 200) {
                return $this->redirect($response->toArray()['redirectUrl']);
            }
            $errorMessage = $response->toArray(false)['errors'][0]['message'];
        }

        return $this->render('order/index.html.twig', [
            'errorMessage' => $errorMessage,
            'form' => $form->createView(),
            'products' => []
        ]);
    }
}
