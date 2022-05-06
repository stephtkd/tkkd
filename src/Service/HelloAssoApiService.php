<?php

namespace App\Service;

/**
 * This class could appear complicated but once prefill documentation in your hands it will make perfect sens !
 * Authentification et récupèration du lien vers le formulaire de paiement via l'API HelloAsso
 */
class HelloAssoApiService
{
    private $helloAsso;
    private $token;

    public function __construct($helloAsso)
    {
        $this->helloAsso = $helloAsso;
        $this->initToken($helloAsso->clientId, $helloAsso->clientSecret);
    }

    private function initToken($clientId, $clientSecret)
    {
        $request = new \HTTP_Request2();
        $request->setUrl('https://api.helloasso.com/oauth2/token');
        $request->setMethod(\HTTP_Request2::METHOD_POST);
        $request->setHeader(array(
            'Content-Type' => 'application/x-www-form-urlencoded',
        ));
        $request->addPostParameter(array(
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret
        ));

        try
        {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                $this->token = json_decode($response->getBody());
            }
            else {
                echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase();
            }
        }
        catch(\HTTP_Request2_Exception $e)
        {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Call HelloAsso API to initialize checkout
     * If ok this function return raw response
     * Else an error code
     */
    public function initCart($data)
    {
        $request = new \HTTP_Request2();
        $request->setUrl('https://api.helloasso.com/v5/organizations/' . $this->helloAsso->organismSlug . '/checkout-intents');
        $request->setMethod(\HTTP_Request2::METHOD_POST);
        $request->setHeader(array(
            'authorization' => 'Bearer ' . $this->token->access_token,
            'Content-Type' => 'application/json',
        ));
        $date = new \Datetime($data->birthdate);

        $body = array('totalAmount' => round($data->amount * 100),
            'initialAmount' => round($data->amount * 100),
            'itemName' => 'Commande sur notre site',
            'backUrl' => $this->helloAsso->baseUrl,
            'errorUrl' => $this->helloAsso->returnUrl,
            'returnUrl' => $this->helloAsso->returnUrl,
            'containsDonation' => false,
            'payer' => array(
                'firstName' => $data->firstname,
                'lastName' => $data->lastname,
                'email' => $data->email,
            ),
            'metadata' => array(
                'reference' => $data->id,
            )
        );

        if($data->method > 1) {
            $body = $this->manageMultiplePayment($data->method, $data->amount * 100, $body);
        }

        $request->setBody(json_encode($body));

        try{
            $response = $request->send();
            return json_decode($response->getBody());
        } catch(\Exception $e){
            return json_decode('{"error":"' . $e . '"}');
        }
    }

    /**
     * Split amount into terms and set terms date to first day of the month
     */
    private function manageMultiplePayment($paymentCount, $totalAmount, $body)
    {
        $termsAmount = round($totalAmount / $paymentCount, 2, PHP_ROUND_HALF_DOWN);
        $rest = round($totalAmount - ($termsAmount * $paymentCount), 2);

        $body['initialAmount'] = $termsAmount;

        $body['terms'] = array();
        $today = getdate();
        $nextPayment = new \DateTime($today['year'] . '-' . $today['month'] . '-01');

        for($i = 1; $i < $paymentCount; $i++) {
            $nextPayment->add(new \DateInterval('P1M'));
            array_push($body['terms'], array(
                'amount' => $i == $paymentCount - 1 ? ($termsAmount + $rest) : $termsAmount,
                'date' => $nextPayment->format('Y-m-d')
            ));
        }

        return $body;
    }
}
