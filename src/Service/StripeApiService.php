<?php

namespace App\Service;

use App\Classe\Cart;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeApiService
{
    public function __construct()
    {
        Stripe::setApiKey($_ENV["API_KEY"]);
    }

    public function generatePaymentLink(Cart $cart) {
        $items = $this->generateLineItems($cart);
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [$items],
            'mode' => 'payment',
            'success_url' => $_ENV["BACK_URL"] . 'order/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV["BACK_URL"] . 'order/resume/cb',
        ]);

        return $checkout_session->url;
    }

    private function generateLineItems(Cart $cart)
    {
        $items = [];
        $description = '';

        foreach ($cart->getFull() as $subscription) {
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