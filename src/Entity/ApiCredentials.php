<?php

namespace App\Entity;

use App\Repository\ApiCredentialsRepository;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiCredentialsRepository::class)
 */
class ApiCredentials
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5000)
     */
    private string $refreshToken;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $expireDateRefreshToken;

    /**
     * @ORM\Column(type="string", length=5000)
     */
    private string $accessToken;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $expireDateAccessToken;

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @param mixed $refreshToken
     */
    public function setRefreshToken($refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExpireDateRefreshToken(): \DateTimeInterface
    {
        return $this->expireDateRefreshToken;
    }

    /**
     * @param \DateTimeInterface $expireDateRefreshToken
     */
    public function setExpireDateRefreshToken(\DateTimeInterface $expireDateRefreshToken): void
    {
        $this->expireDateRefreshToken = $expireDateRefreshToken;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExpireDateAccessToken(): \DateTimeInterface
    {
        return $this->expireDateAccessToken;
    }

    /**
     * @param \DateTimeInterface $expireDateAccessToken
     */
    public function setExpireDateAccessToken(\DateTimeInterface $expireDateAccessToken): void
    {
        $this->expireDateAccessToken = $expireDateAccessToken;
    }

}