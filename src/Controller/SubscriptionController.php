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
use App\Service\HelloAssoApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
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
        ): Response {
        $event = $this->entityManager->getRepository(Event::class)->findOneBy(['id' => $id]);
        $listMember = $this->entityManager->getRepository(Member::class)->findBy(['responsibleAdult' => $this->getUser()->getId()]);
        $listEventRate = $this->entityManager->getRepository(EventRate::class)->findAll();
        $listEventOption = $this->entityManager->getRepository(EventOption::class)->findAll();

        return $this->render('subscription/index.html.twig', [
            'event' => $event,
            'listMember' => $listMember,
            'listEventRate' => $listEventRate,
            'listEventOption' => $listEventOption,
            'subscriptionId' => $id,
        ]);
    }

    #[Route('/subscription/{id}/getCart', name: 'app_get_cart')]
    public function getCart($id, SessionInterface $session):JsonResponse
    {
        return new JsonResponse([
            $session->get('cart',[])
        ]);
    }
    

    #[Route('/subscription/{id}/add-member-event-subscription', name: 'add_member_event_subscription')]
    public function addMemberEventSubscription(
        Request $request,
        ValidatorInterface $validator,
        SessionInterface $sessionInterface,
        $id
    ): Response {
        $cart = null;
        $tabValues = $request->request->all();
        $eventSubscription = null;
        $member = null;
        $i = 0;
        $indexMember = "";
        $total = 0;
        $payment = null;

        if($tabValues['key-bool-checked'] > 0){
            $cart = new Cart($this->entityManager, $sessionInterface);
            $event = $this->entityManager->getRepository(Event::class)->findOneBy(['id' => $id]);

            foreach($tabValues as $key => $value){

                if(str_contains($key,"key-member-")){
                    $indexMember = explode("key-member-",$key)[1];
                }
    
                if(str_contains($key,"key-member") && $value !== "" && $i === 0){
                    $member = $this->entityManager->getRepository(Member::class)->findOneBy(['id' => $value]);
                    $eventSubscriptionExist = $this->entityManager->getRepository(EventSubscription::class)->findBy([
                        'member' => $member->getId(),
                        'event' => $event->getId()
                    ]);

                    if(!$eventSubscriptionExist){
                        $payment = new Payment();
                        $payment->setStatus('ok');
                        $payment->setAmount(1500);
                        $payment->setDate(new \DateTime());
                        $payment->setMean('Espèce');
                    }
                    
                    $i++;
    
                }else if(!$eventSubscriptionExist && $key === "key-event-rate-member-".$indexMember && $value !== "" && $tabValues['key-member-'.$indexMember] !== "" ){
                    $eventRate = $this->entityManager->getRepository(EventRate::class)->findOneById($value);

                    $eventSubscription = new EventSubscription();
                    $eventSubscription->setStatus('ok');
                    $eventSubscription->setEvent($event);
                    $eventSubscription->setEventRate($eventRate);
                    $eventSubscription->setMember($member);
                    $eventSubscription->setUser($this->getUser());
                    $eventSubscription->setIsPaid(false);
                    $total += $eventRate->getAmount();


                }else if(!$eventSubscriptionExist && str_contains($key,"key-event-option-member-".$indexMember) && $value !== ""){
                    $valueEventOption = $this->entityManager->getRepository(EventOption::class)->findOneByName($value);
                    $eventSubscription->addEventOption($valueEventOption);
                    
                }else if(str_contains($key,"key-member-") && $i !== 0){
                    
                    if($eventSubscription){
                        $details = [
                            'user' => [
                                'id' => $eventSubscription->getUser()->getId(),
                                'firstName' => $eventSubscription->getUser()->getFirstName(),
                                'lastName' => $eventSubscription->getUser()->getLastName(),
                                'email' => $eventSubscription->getUser()->getEmail(),
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
                                    'id' => $eventSubscription->getEventRate()->getId(),
                                    'name' => $eventSubscription->getEventRate()->getName(),
                                    'amount' => $eventSubscription->getEventRate()->getAmount(),
                                ],
                                'options' => [],
                            ],
                        ];
                        
                        foreach ($eventSubscription->getEventOptions() as $option) {
                            $opt = [
                                'id' => $option->getId(),
                                'name' => $option->getName(),
                                'amount' => $option->getAmount(),
                            ];
                            array_push($details['event']['options'], $opt);
                        }

                        if($payment){
                            $payment->setDetails($details);
                            $eventSubscription->setPayment($payment);
                            $cart->add($eventSubscription);
                            $this->entityManager->persist($payment);
                        }
                    }
                    

                    if($value !== ""){
                        $member = $this->entityManager->getRepository(Member::class)->findOneBy(['id' => $value]);
                        $eventSubscriptionExist = $this->entityManager->getRepository(EventSubscription::class)->findBy([
                            'member' => $member->getId(),
                            'event' => $event->getId()
                        ]);

                        if(!$eventSubscriptionExist){
                            $payment = new Payment();
                            $payment->setStatus('ok');
                            $payment->setAmount(1500);
                            $payment->setDate(new \DateTime());
                            $payment->setMean('Espèce');
                            $this->entityManager->persist($payment);
                        }
                        
                        $i++;
                    }

                    
                }

                
    
                // $errors = $validator->validate($eventSubscription);
    
                // if (count($errors) > 0) {
                //     $errorsString = $errors->get(0)->getMessage();

                //     return $this->redirectToRoute('app_subscription',[
                //         'id' => $id
                //     ]);
                // }
            }

            if($eventSubscription && $tabValues['key-bool-checked'] == 1){
                $details = [
                    'user' => [
                        'id' => $eventSubscription->getUser()->getId(),
                        'firstName' => $eventSubscription->getUser()->getFirstName(),
                        'lastName' => $eventSubscription->getUser()->getLastName(),
                        'email' => $eventSubscription->getUser()->getEmail(),
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
                            'id' => $eventSubscription->getEventRate()->getId(),
                            'name' => $eventSubscription->getEventRate()->getName(),
                            'amount' => $eventSubscription->getEventRate()->getAmount(),
                        ],
                        'options' => [],
                    ],
                ];
                
                foreach ($eventSubscription->getEventOptions() as $option) {
                    $opt = [
                        'id' => $option->getId(),
                        'name' => $option->getName(),
                        'amount' => $option->getAmount(),
                    ];
                    array_push($details['event']['options'], $opt);
                }

                if($payment){
                    $payment->setDetails($details);
                    $eventSubscription->setPayment($payment);
                    $cart->add($eventSubscription);
                    $this->entityManager->persist($payment);
                }
                
            }

        }
        
        if($eventSubscription){
            $this->entityManager->persist($eventSubscription);
            $this->entityManager->flush();
        }
        

        return $this->redirectToRoute('order_resume_esp', [
            'total' => $total, 
            'cart' => $cart
            // 'cart' => $cart->getOne($eventSubscription->getId())
        ]);

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
        $cart = $session->get('cart',[]);

        dump($request->request->get('output'));

        
        if ($request->request->get('output')) {
            $cart = $request->request->get('output');
            $session->set('cart',$cart);
        }


        return new JsonResponse([
            'success' => true,
        ]);
    }

    #[Route('/order/resumeEsp', name: 'order_resume_esp')]
    public function checkoutEsp(SessionInterface $session): Response
    {
        $eventSubscriptions = [];
        // $this->addToCart($cart);
        // if (0 != $total) {
            // foreach ($cart->getFull() as $subscription) {
            //     $this->entityManager->persist($subscription);
            // }

            // $this->entityManager->flush();

        //     return $this->redirectToRoute('app_subscribe_event');
        // }
        $carts = $session->get('cart',[]);

        foreach($carts as $cart){
            $eventSubscriptions[] = $this->cartToEventSubscription($cart);

        }

        return $this->render('order/resume.html.twig', [
            'subscriptions' => $cart->get(),
            'mean' => $mean
        ]);
    }

    private function cartToEventSubscription($cart){
        $event = $this->entityManager->getRepository(Event::class)->findOneBy(['id' => $cart['event']]);
        $member = $this->entityManager->getRepository(Member::class)->findOneBy(['id' => $cart['member']]);

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
                'options' => [],
            ],
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

        return $subscription;

    }  

    // Use to test, fill up these data with subscription form
    private function addToCart(Cart $cart)
    {
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
                'options' => [],
            ],
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
