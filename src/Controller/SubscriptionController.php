<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Event;
use App\Entity\EventOption;
use App\Entity\EventRate;
use App\Entity\EventSubscription;
use App\Entity\Member;
use App\Entity\Payment;
use App\Service\StripeApiService;
use App\Form\EventSubscriptionType;
use App\Form\OrderType;
use App\Form\SubscriptionType;
use App\Repository\EventRepository;
use App\Service\HelloAssoApiService;
use ContainerMQNbkTr\getFollowedMembershipControllerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscriptionController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private StripeApiService $apiService;

    public function __construct(EntityManagerInterface $entityManager, StripeApiService $apiService)
    {
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
    }

    #[Route('/subscription/{id}', name: 'app_subscription')]
    public function index(
        $id, 
        Request $request
        ): Response
    {
        $event = $this->entityManager->getRepository(Event::class)->findOneBy(['id' => $id]);
        $listMember = $this->entityManager->getRepository(Member::class)->findAll();
        $listEventRate = $this->entityManager->getRepository(EventRate::class)->findAll();
        $listEventOption = $this->entityManager->getRepository(EventOption::class)->findAll();

        return $this->render('subscription/index.html.twig', [
            'event' => $event,
            'listMember' => $listMember,
            'listEventRate' => $listEventRate,
            'listEventOption' => $listEventOption,
            'subscriptionId' =>$id
        ]);
    }

    #[Route('/subscription/{id}/add-member-event-subscription', name: 'add_member_event_subscription')]
    public function addMemberEventSubscription(
        Request $request,
        ValidatorInterface $validator,
        SessionInterface $sessionInterface,
        $id
    ):Response
    {

        $output = $request->request->get('output');
        dump($output);
        $cart = new Cart($this->entityManager, $sessionInterface);
        $event = $this->entityManager->getRepository(Event::class)->findOneBy(['id' => $id]);

        foreach($output as $value){
            $member = $this->entityManager->getRepository(Member::class)->findOneBy(['id' => $value['member']]);
            $eventRate = $this->entityManager->getRepository(EventRate::class)->findOneById($value['eventRate']);

            $eventSubsciption = new EventSubscription();

            $eventSubsciption->setStatus('ok');
            $eventSubsciption->setEvent($event);
            $eventSubsciption->setEventRate($eventRate);
            $eventSubsciption->setMember($member);

            if(array_key_exists('eventOption', $value)){

                foreach($value['eventOption'] as $eventOption){
                    $valueEventOption = $this->entityManager->getRepository(EventOption::class)->findOneById($eventOption);
                    $eventSubsciption->addEventOption($valueEventOption);
                }
            }
            
            $eventSubsciption->setUser($this->getUser());

            $payment = new Payment();
            $payment->setStatus('ok');
            $payment->setAmount(1500);
            $payment->setDate(new \DateTime());
            $payment->setMean('Espèce');

            $details = [
                'user' => [
                    'id' => $eventSubsciption->getUser()->getId(),
                    'firstName' => $eventSubsciption->getUser()->getFirstName(),
                    'lastName' => $eventSubsciption->getUser()->getLastName(),
                    'email' => $eventSubsciption->getUser()->getEmail(),
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
                        'id' => $eventSubsciption->getEventRate()->getId(),
                        'name' => $eventSubsciption->getEventRate()->getName(),
                        'amount' => $eventSubsciption->getEventRate()->getAmount(),
                    ],
                    'options' => []
                ]
            ];
            
            foreach ($eventSubsciption->getEventOptions() as $option) {
                $opt = [
                    'id' => $option->getId(),
                    'name' => $option->getName(),
                    'amount' => $option->getAmount(),
                ];
                array_push($details['event']['options'], $opt);
            }

            $payment->setDetails($details);
            $eventSubsciption->setPayment($payment);
            $cart->add($eventSubsciption);
        }

        $errors = $validator->validate($eventSubsciption);
        
        if (count($errors) > 0) {
            $errorsString = $errors->get(0)->getMessage();
            
            return new JsonResponse([
                'success' => false,
                'message' => $errorsString
            ]);
        }
    
        return $this->redirectToRoute('order_resume_esp',['total' => 0.0,'cart' => $cart]);

        // return new JsonResponse([
        //     'success' => true,
        //     'message' => "L'enregistrement a été validé."
        // ]);
    }

    public function new(Request $request): Response
    {
        $eventSubscription = new EventSubscription();
        $form = $this->createForm(EventSubscriptionType::class, $eventSubscription);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eventSubscription = $form->getData();


            return $this->redirectToRoute('app_subscription');
        }

        return $this->renderForm('subscription/index.html.twig', [
            'form' => $form->createView(),
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
