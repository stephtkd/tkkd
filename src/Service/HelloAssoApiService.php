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

    private string $organizationSlug = 'taekwonkido-phenix';

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
            'totalAmount' => 1000,
            'initialAmount' => 1000,
            'itemName' => 'Abonnement -15 ans',
            "backUrl" => "https://www.partnertest.com/cancel.php",
            "errorUrl" => "https://www.partnertest.com/cancel.php",
            "returnUrl" => "https://www.partnertest.com/cancel.php",
            "containsDonation" => true,
            "terms[].amount" => 1000,
            "terms[].date" => (new DateTime())->format('Y-m-d'),
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
        ]);

        $url = 'https://api.helloasso.com/v5/organizations/' . $this->organizationSlug . '/checkout-intents';

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
                'client_id' => '2b5a70e036244abfaa7b9732e58bc21b',
                'client_secret' => 'Xk39PKKAZSgV+fTEeWUG0HTTVFJbK2lL'
            ];

            $response = $this->callApi($url, $requestHeader, $requestBody)->toArray();

            $credentials = new ApiCredentials();
        } else {
            $requestBody = [
                'grant_type' => 'refresh_token',
                'client_id' => '2b5a70e036244abfaa7b9732e58bc21b',
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
