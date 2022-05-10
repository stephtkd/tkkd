<?php

namespace App\Service;

use App\Entity\ApiCredentials;
use App\Repository\ApiCredentialsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HelloAssoApiService
{
    private EntityManagerInterface $manager;
    private HttpClientInterface $client;
    private ApiCredentialsRepository $apiCredentialsRepository;

    public function __construct(EntityManagerInterface $manager, HttpClientInterface $client, ApiCredentialsRepository $apiCredentialsRepository)
    {
        $this->manager = $manager;
        $this->client = $client;
        $this->apiCredentialsRepository = $apiCredentialsRepository;
    }

    public function generateCheckoutLink($data)
    {
        $requestHeader = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->generateAccessToken()
        ];

        $requestBody = json_encode([
            'totalAmount' => $data["amount"],
            'initialAmount' => $data["amount"],
            'itemName' => "Abonnement Taekwonkido",
            "backUrl" => $_ENV["BACK_URL"],
            "errorUrl" => $_ENV["BACK_URL"],
            "returnUrl" => $_ENV["BACK_URL"],
            "containsDonation" => false,
            "terms[].amount" => $data["amount"],
            "terms[].date" => (new DateTime())->format('Y-m-d'),
            "payer" => [
                "firstName" => $data["firstName"],
                "lastName" => $data["lastName"],
                "email" => $data["email"],
                "dateOfBirth" => "1986-07-06T00:00:00+02:00",
                "address" => "23 rue du palmier",
                "city" => "Paris",
                "zipCode" => "75000",
                "country" => "FRA",
            ]
        ]);

        $url = 'https://api.helloasso.com/v5/organizations/' . $_ENV['ORGANIZATION_SLUG'] . '/checkout-intents';

        return $this->callApi($url, $requestHeader, $requestBody)->toArray();
    }

    // Use or create the refresh token to generate the access token
    private function generateAccessToken() {
        $apiCredentials = $this->apiCredentialsRepository->findAll();

        if (sizeof($apiCredentials) != 0 && $apiCredentials[0]->getExpireDateAccessToken() > new DateTime()) {
            return $apiCredentials[0]->getAccessToken();
        }

        $requestHeader = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $url = 'https://api.helloasso.com/oauth2/token';

        if (sizeof($apiCredentials) == 0 || $apiCredentials[0]->getExpireDateRefreshToken() < new DateTime()) {
            $requestBody = [
                'grant_type' => 'client_credentials',
                'client_id' => $_ENV['CLIENT_ID'],
                'client_secret' => $_ENV['CLIENT_SECRET']
            ];

            $response = $this->callApi($url, $requestHeader, $requestBody)->toArray();

            $credentials = new ApiCredentials();
        } else {
            $requestBody = [
                'grant_type' => 'refresh_token',
                'client_id' => $_ENV['CLIENT_ID'],
                'refresh_token' => $apiCredentials[0]->getRefreshToken()
            ];

            $response = $this->callApi($url, $requestHeader, $requestBody)->toArray();

            $credentials = $apiCredentials[0];
        }

        $credentials->setAccessToken($response['access_token']);
        $credentials->setExpireDateAccessToken((new DateTime())->modify('+30 minutes'));
        $credentials->setRefreshToken($response['refresh_token']);
        $credentials->setExpireDateRefreshToken((new DateTime())->modify('+1 month'));

        $this->manager->persist($credentials);
        $this->manager->flush();

        return $response['access_token'];
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
        } catch (TransportExceptionInterface $e) {
            return '{"error":"' . $e . '"}';
        }

        return $response;
    }
}