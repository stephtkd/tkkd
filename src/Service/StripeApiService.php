<?php

namespace App\Service;

use Stripe\Stripe;
use App\Classe\Cart;
use Stripe\Checkout\Session;
use App\Repository\CredentialRepository;

class StripeApiService
{
    private CredentialRepository $credentialRepository;
    
    public function __construct(CredentialRepository $credentialRepository) 
    {
        $credential = $credentialRepository->findOneBy(['id' => 1]);
        Stripe::setApiKey($credential->getApiKey());
        
    }

    public function generatePaymentLink(Cart $cart) {
        $items = $this->generateLineItems($cart);
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [$items],
            'mode' => 'payment',
            'success_url' =>  "http://$_SERVER[HTTP_HOST]" . '/order/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' =>  "http://$_SERVER[HTTP_HOST]" . '/order/resume/cb',
        ]);

        return $checkout_session->url;
    }

    private function generateLineItems(Cart $cart)
    {
        $items = [];

        foreach ($cart->get() as $subscription) {
            $description = '';
            foreach ($subscription->getEventOptions() as $option) {
                $description .= $option->getName().', ';
            }
            array_push($items, [
                'currency' => 'eur',
                'name' => $subscription->getEvent()->getName(),
                'description' => $description,
                'amount' => $subscription->getAmount(),
                'quantity' => 1]);
        }

        return $items;
    }
}