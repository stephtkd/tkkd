<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Event;
use App\Entity\EventOption;
use App\Entity\EventRate;
use App\Entity\EventSubscription;
use App\Entity\Invoice;
use App\Entity\Member;
use App\Entity\Payment;
use App\Service\StripeApiService;
use App\Form\EventSubscriptionType;
use App\Form\InvoiceType;
use App\Form\MemberEventSubscriptionType;
use App\Form\OrderType;
use App\Form\TaskType;
use App\Repository\EventSubscriptionRepository;
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
        Event $event,
        Request $request
        ): Response {
        $listEventRate = $this->entityManager->getRepository(EventRate::class)->findAll();
        $listEventOption = $this->entityManager->getRepository(EventOption::class)->findBy(['event' => $event->getId()]);
        $listEventSubscription = $this->entityManager->getRepository(EventSubscription::class)->findBy(['event' =>$event->getId()]);

        $eventSubscription = new EventSubscription();
        $options['responsibleAdult'] = $this->getUser()->getId();
        $options['event'] = $event->getId();
        $resultListEventOption = [];
        $i=1;

        foreach($listEventOption as $value){
            $resultListEventOption[$value->getName()] = $i; 
            $i++;
        }

        $options['eventOptions'] = $resultListEventOption;
        $invoice = new Invoice();
        $form = $this->createForm(InvoiceType::class, $invoice, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoice = $form->getData();
            $tabValues = $request->request->all();
            $eventSubscription = $form->getData();

            foreach($tabValues['member_event_subscription']['eventOption'] as $value){
                $eventOption = $this->entityManager->getRepository(EventOption::class)->findOneById($value);
                $eventSubscription->addEventOption($eventOption);

            }
            $eventSubscription->setStatus('ok');
            $eventSubscription->setEvent($event);
            $eventSubscription->setUser($this->getUser());


            //PAYMENT
            $payment = new Payment();
            $payment->setStatus('ok');
            $payment->setAmount(15);
            $payment->setDate(new \DateTime());
            $payment->setMean('Espèce');
            $details = [
                'user' => [
                    'id' => $eventSubscription->getUser()->getId(),
                    'firstName' => $eventSubscription->getUser()->getFirstName(),
                    'lastName' => $eventSubscription->getUser()->getLastName(),
                    'email' => $eventSubscription->getUser()->getEmail(),
                ],
                'member' => [
                    'id' => $eventSubscription->getMember()->getId(),
                    'firstName' => $eventSubscription->getMember()->getFirstName(),
                    'lastName' => $eventSubscription->getMember()->getLastName(),
                    'email' => $eventSubscription->getMember()->getEmail(),
                    'street' => $eventSubscription->getMember()->getStreetAddress(),
                    'postalCode' => $eventSubscription->getMember()->getPostalCode(),
                    'city' => $eventSubscription->getMember()->getCity(),
                    'country' => $eventSubscription->getMember()->getNationality(),
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
            $payment->setDetails($details);
            $eventSubscription->setPayment($payment);


            $this->entityManager->persist($eventSubscription);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_subscription',['id' => $id]);
        }
        
        foreach($listEventSubscription as $value){
            $invoice->getEventSubscriptions()->add($value);
        }


        return $this->render('subscription/index.html.twig', [
            'event' => $event,
            'listEventSubscription' => $listEventSubscription,
            'listEventRate' => $listEventRate,
            'listEventOption' => $listEventOption,
            'subscriptionId' => $event->getId(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/subscription/{id}/updateEventSubscription/{idMember}', name:'update_event_subscription')]
    public function updateEventSubscription(
        $id, 
        $idMember,
        EventSubscriptionRepository $eventSubscriptionRepository
        ): Response
    {

        $eventSubscription = $eventSubscriptionRepository->findOneBy([
            'event' => $id,
            'member' => $idMember
        ]);

        
        return $this->redirectToRoute('app_subscription',['id' =>$id]);

    }

    #[Route('/subscription/{id}/getCart', name: 'app_get_cart')]
    public function getCart($id, SessionInterface $session):JsonResponse
    {
        return new JsonResponse([
            $session->get('cart',[])
        ]);
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

    // #[Route('/order/resumeEsp', name: 'order_resume_esp')]
    // public function checkoutEsp(Cart $cart): Response
    // {
    //     $cart->persistCart();
        
    //     return $this->redirectToRoute('app_subscribe_event');
    // }

    #[Route('/order/success/{session_id}', name: 'order_success')]
    public function success($session_id, Cart $cart): Response
    {
        $cart->persistCart($session_id);

        return $this->render('order/success.html.twig');
    }

    // #[Route('/order/resume/{mean}', name: 'order_resume')]
    // public function checkout(string $mean, Cart $cart): Response
    // {
    //     $cart = $session->get('cart',[]);

    //     if ($request->request->get('output')) {
    //         $cart = $request->request->get('output');
    //         $session->set('cart',$cart);
    //     }


    //     return new JsonResponse([
    //         'success' => true,
    //     ]);
    // }

    #[Route('/subscription/{id}/confirmResumeEsp',name:'confirm_resume_esp')]
    public function confirmResumeEsp(
        $id,
        EventSubscriptionRepository $eventSubscriptionRepository
        ):Response
    {
        $listEventSubscription = $eventSubscriptionRepository->findBy([
            'event' => $id
        ]);

        foreach($listEventSubscription as $value){
            $value->setIsPaid(true);
        }

        $this->entityManager->flush();

        
        return $this->redirectToRoute('app_subscription',['id' =>$id]);
    }

    #[Route('/subscription/{id}/deleteEventSubscription/{idMember}', name:'delete_event_subscription')]
    public function deleteEventSubscription(
        $id, 
        $idMember,
        EventSubscriptionRepository $eventSubscriptionRepository
        ): Response
    {

        $eventSubscription = $eventSubscriptionRepository->findOneBy([
            'event' => $id,
            'member' => $idMember
        ]);

        $this->entityManager->remove($eventSubscription);
        $this->entityManager->flush();
        
        return $this->redirectToRoute('app_subscription',['id' =>$id]);

    }

    #[Route('/subscription/{id}/deletCart', name: 'delete_cart')]
    public function deleteCart(
        $id, 
        Request $request,
        SessionInterface $session
        ): Response 
    {
        $cart = $session->get('cart',[]);
        $session->remove('cart');

        return $this->redirectToRoute('app_subscription',[
            'id' => $id
        ]);
    }


    #[Route('/order/{id}/resumeEsp', name: 'order_resume_esp')]
    public function checkoutEsp($id,SessionInterface $session): Response
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

        return $this->render('order/resumeEsp.html.twig', [
            'subscriptions' => $eventSubscriptions,
            'subscriptionId' => $id,
            'message' => "Merci de faire l'appoint auprès de l'organisateur."
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

        for($i = 0; array_key_exists('eventOption',$cart) && $i< count($cart['eventOption']); $i++ ){
            $subscription->addEventOption($cart['eventOption'][$i]);
        }
        
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













    #[Route('/subscription/{id}/invoice', name: 'app_invoice')]
    public function invoice(
        $id,
        Request $request
        ): Response {
        $event = $this->entityManager->getRepository(Event::class)->findOneBy(['id' => $id]);
        $listEventRate = $this->entityManager->getRepository(EventRate::class)->findAll();
        $listEventOption = $this->entityManager->getRepository(EventOption::class)->findBy(['event' => $id]);
        $listEventSubscription = $this->entityManager->getRepository(EventSubscription::class)->findBy(['event' =>$id]);

        $eventSubscription = new EventSubscription();
        // $eventSubscription->setMember($this->entityManager->getRepository(Member::class)->findOneBy(['id' => 1]));
        $options['responsibleAdult'] = $this->getUser()->getId();
        $options['event'] = $id;
        $resultListEventOption = [];
        $i=1;

        foreach($listEventOption as $value){
            $resultListEventOption[$value->getName()] = $i; 
            $i++;
        }

        $options['eventOptions'] = $resultListEventOption;
        $invoice = new Invoice();
        // $invoice->getEventSubscriptions()->add($eventSubscription);

        foreach($listEventSubscription as $eventSubscription){
            $invoice->getEventSubscriptions()->add($eventSubscription);
        }
        $form = $this->createForm(InvoiceType::class,$invoice,$options);
        // $form = $this->createForm(MemberEventSubscriptionType::class,$eventSubscription,$options);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tabValues = $request->request->all();
            $eventSubscription = $form->getData();

            foreach($tabValues['member_event_subscription']['eventOption'] as $value){
                $eventOption = $this->entityManager->getRepository(EventOption::class)->findOneById($value);
                $eventSubscription->addEventOption($eventOption);

            }
            $eventSubscription->setStatus('ok');
            $eventSubscription->setEvent($event);
            $eventSubscription->setUser($this->getUser());


            //PAYMENT
            $payment = new Payment();
            $payment->setStatus('ok');
            $payment->setAmount(15);
            $payment->setDate(new \DateTime());
            $payment->setMean('Espèce');
            $details = [
                'user' => [
                    'id' => $eventSubscription->getUser()->getId(),
                    'firstName' => $eventSubscription->getUser()->getFirstName(),
                    'lastName' => $eventSubscription->getUser()->getLastName(),
                    'email' => $eventSubscription->getUser()->getEmail(),
                ],
                'member' => [
                    'id' => $eventSubscription->getMember()->getId(),
                    'firstName' => $eventSubscription->getMember()->getFirstName(),
                    'lastName' => $eventSubscription->getMember()->getLastName(),
                    'email' => $eventSubscription->getMember()->getEmail(),
                    'street' => $eventSubscription->getMember()->getStreetAddress(),
                    'postalCode' => $eventSubscription->getMember()->getPostalCode(),
                    'city' => $eventSubscription->getMember()->getCity(),
                    'country' => $eventSubscription->getMember()->getNationality(),
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
            $payment->setDetails($details);
            $eventSubscription->setPayment($payment);


            $this->entityManager->persist($eventSubscription);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_subscription',['id' => $id]);
        }

        return $this->render('subscription/invoice.html.twig', [
            'event' => $event,
            // 'listMember' => $listMember,
            'listEventSubscription' => $listEventSubscription,
            'listEventRate' => $listEventRate,
            'listEventOption' => $listEventOption,
            'subscriptionId' => $id,
            'form' => $form->createView()
        ]);
    }

}
