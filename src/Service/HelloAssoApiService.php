<?php

namespace App\Service;

use App\Entity\ApiCredentials;
use App\Repository\ApiCredentialsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HelloAssoApiService
{
    private EntityManagerInterface $manager;
    private HttpClientInterface $client;
    private ApiCredentialsRepository $apiCredentialsRepository;

    private string $organizationSlug = 'taekwonkido-phenix';

    public function __construct(EntityManagerInterface $manager, HttpClientInterface $client, ApiCredentialsRepository $apiCredentialsRepository)
    {
        $this->manager = $manager;
        $this->client = $client;
        $this->repo = $apiCredentialsRepository;
        $this->initCart([]);
    }

    public function initCart($data)
    {
        $requestHeader = [
            'headers' =>
                [
                    'Bearer' => $this->generateAccessToken(),
                    'Content-Type' => 'application/json'
                ]
        ];
        $requestBody = [
            'totalAmount' => 10,
            'initialAmount' => 10,
            'itemName' => 'Abonnement -15 ans',
            "backUrl" => "/",
            "errorUrl" => "/",
            "returnUrl" => "/",
            "containsDonation" => false,
            "payer" => [
                "firstName" => "John",
                "lastName" => "Doe",
                "email" => "john.doe@test.com",
                "dateOfBirth" => "1986-07-06T00:00:00+02:00",
                "address" => "23 rue du palmier",
                "city" => "Paris",
                "zipCode" => "75000",
                "country" => "FRA",
            ]
        ];

        $url = 'https://api.helloasso.com/v5/organizations/' . $this->organizationSlug . '/checkout-intents';

        return json_decode($this->callApi($url, $requestHeader, $requestBody));
    }

    // Use or create the refresh token to generate the access token
    private function generateAccessToken() {
        $requestHeader = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $url = 'https://api.helloasso.com/oauth2/token';

        $apiCredentials = $this->repo->findAll();

        if (sizeof($apiCredentials) == 0 ) {
            $requestBody = [
                'grant_type' => 'client_credentials',
                'client_id' => '2b5a70e036244abfaa7b9732e58bc21b',
                'client_secret' => 'Xk39PKKAZSgV+fTEeWUG0HTTVFJbK2lL'
            ];

            $content = json_decode($this->callApi($url, $requestHeader, $requestBody));

            $credentials = new ApiCredentials();
            $credentials->setRefreshToken($content['refresh_token']);

            $this->manager->persist($credentials);
        } else {
            $requestBody = [
                'grant_type' => 'refresh_token',
                'client_id' => '2b5a70e036244abfaa7b9732e58bc21b',
                'refresh_token' => $apiCredentials[0]->getRefreshToken()
            ];

            $content = json_decode($this->callApi($url, $requestHeader, $requestBody));
        }

        return $content['access_token'];
    }

    private function callApi($url, $header, $body) {
        try {
            $response = $this->client->request(
                'POST',
                $url,
                [
                    'headers' => $header,
                    'body' => $body
                ]
            );
        } catch (\Exception $e) {
            return '{"error":"' . $e . '"}';
        }

        return $response->getContent();
    }
}
