<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Event;
use App\Entity\EventSubscription;
use App\Entity\Member;
use App\Entity\Payment;
use App\Form\OrderType;
use App\Form\SubscriptionType;
use App\Repository\EventRepository;
use App\Service\HelloAssoApiService;
use ContainerMQNbkTr\getFollowedMembershipControllerService;
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

    #[Route('/subscription/{id}', name: 'app_subscription')]  // order
    public function index($id, Request $request): Response
    {
        $event = $this->entityManager->getRepository(Event::class)->findOneBy(['id' => $id]);

        return $this->render('subscription/index.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/order/resumeCb', name: 'order_resume_cb')]
    public function checkoutCb(Request $request): Response
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

        return $this->render('order/resumeCb.html.twig', [
            'errorMessage' => $errorMessage,
            'form' => $form->createView(),
            'products' => []
        ]);
    }

    #[Route('/order/resumeEsp/{total}', name: 'order_resume_esp')]
    public function checkoutEsp(float $total, Cart $cart): Response
    {
        $this->addToCart($cart);
        if ($total != 0) {
            foreach ($cart->getFull() as $subscription) {
                $this->entityManager->persist($subscription);
            }

            $this->entityManager->flush();

            return $this->redirectToRoute('app_subscribe_event');
        }

        return $this->render('order/resumeEsp.html.twig', [
            'subscriptions' => $cart->getFull(),
        ]);
    }

    // Use to test, fill up these data with subscription form
    private function addToCart(Cart $cart) {
        $event = $this->entityManager->getRepository(Event::class)->findOneBy(['id' => 1]);
        $member = $this->entityManager->getRepository(Member::class)->findOneBy(['id' => 1]);

        $subscription = new EventSubscription();
        $payment = new Payment();

        $subscription->setStatus('Paiement en cours');
        $subscription->setEvent($event);
        $subscription->setEventRate($event->getEventRates()[0]);
        $subscription->setMember($member);
        $subscription->addEventOption($event->getEventOptions()[0]);
        $subscription->addEventOption($event->getEventOptions()[1]);
        $subscription->setUser($this->getUser());

        $payment->setStatus('En attente');
        $payment->setAmount(1500);
        $payment->setDate(new \DateTime());
        $payment->setMean('Espèce');
        $details = [
            'user' => [
                'id' => $subscription->getUser()->getId(),
                'firstName' => $subscription->getUser()->getFirstName(),
                'lastName' => $subscription->getUser()->getLastName(),
                'email' => $subscription->getUser()->getEmail(),
            ],
            'member' => [
                'id' => $member->getId(),
                'firstName' => $member->getFirstName(),
                'lastName' => $member->getLastName(),
                'email' => $member->getEmail(),
                'street' => $member->getStreetAdress(),
                'postalCode' => $member->getPostalCode(),
                'city' => $member->getCity(),
                'country' => $member->getNationality(),
            ],
            'event' => [
                'id' => $event->getId(),
                'slug' => $event->getSlug(),
                'startDate' => $event->getStartDate(),
                'endDate' => $event->getEndDate(),
                'rate' => [
                    'id' => $subscription->getEventRate()->getId(),
                    'name' => $subscription->getEventRate()->getName(),
                    'amount' => $subscription->getEventRate()->getAmount(),
                ],
                'options' => []
            ]
        ];
        foreach ($subscription->getEventOptions() as $option) {
            $opt = [
                'id' => $option->getId(),
                'name' => $option->getName(),
                'amount' => $option->getAmount(),
            ];
            array_push($details['event']['options'], $opt);
        }
        $payment->setDetails($details);
        $subscription->setPayment($payment);

        $cart->add($subscription);
    }
}
