<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Event;
use App\Entity\EventSubscription;
use App\Entity\Member;
use App\Entity\Payment;
use App\Service\StripeApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private StripeApiService $apiService;

    public function __construct(EntityManagerInterface $entityManager, StripeApiService $apiService)
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
    public function checkoutCb(Cart $cart): Response
    {
//        $errorMessage = "";
//        $form = $this->createForm(OrderType::class);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
        return $this->redirect($this->apiService->generatePaymentLink($cart));
//            $response = $this->apiService->generateCheckoutLink($form->getData());
//
//            if ($response->getStatusCode() == 200) {
//                return $this->redirect($response->toArray()['redirectUrl']);
//            }
//            $errorMessage = $response->toArray(false)['errors'][0]['message'];
//        }
//
//        return $this->render('order/resumeCb.html.twig', [
//            'errorMessage' => $errorMessage,
//            'form' => $form->createView(),
//            'products' => []
//        ]);
    }

    #[Route('/order/resumeEsp', name: 'order_resume_esp')]
    public function checkoutEsp(Cart $cart): Response
    {
        $cart->persistCart();
        
        return $this->redirectToRoute('app_subscribe_event');
    }

    #[Route('/order/success/{session_id}', name: 'order_success')]
    public function success($session_id, Cart $cart): Response
    {
        $cart->persistCart($session_id);

        return $this->render('order/success.html.twig');
    }

    #[Route('/order/resume/{mean}', name: 'order_resume')]
    public function checkout(string $mean, Cart $cart): Response
    {
        $cart->remove();
        $this->addToCart($cart);

        return $this->render('order/resume.html.twig', [
            'subscriptions' => $cart->get(),
            'mean' => $mean
        ]);
    }

    // Use to test, fill up these data with subscription form
    private function addToCart(Cart $cart) {
        $event = $this->entityManager->getRepository(Event::class)->findOneBy(['id' => 1]);
        $member = $this->entityManager->getRepository(Member::class)->findOneBy(['id' => 1]);

        $subscription = new EventSubscription();
        $payment = new Payment();

        $subscription->setStatus('ok');
        $subscription->setEvent($event);
        $subscription->setEventRate($event->getEventRates()[0]);
        $subscription->setMember($member);
        $subscription->addEventOption($event->getEventOptions()[0]);
        $subscription->addEventOption($event->getEventOptions()[1]);
        $subscription->setUser($this->getUser());

        $payment->setStatus('ok');
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
                'street' => $member->getStreetAddress(),
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
