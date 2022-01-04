<?php

namespace App\Entity;

use App\Repository\EventPageRepository;
use App\Validator\Antispam;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=EventPageRepository::class)
 *
 * @UniqueEntity (
 *     fields={"name"},
 *     message="erreur l'événement existe déjà",
 *     groups="registration"
 * )
 */
class EventPage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length (
     * min = 2,
     * max = 30,
     * minMessage="Votre nom d'événement doit faire au moins {{ limit }} caractères",
     * maxMessage="Votre nom d'événement doit faire au moins doit faire au moins {{ limit }} caractères",
     * groups="all"
     *
     * )
     *
     * @Antispam(message="Votre nom de produit : %string% ne doit contenir que des caractères alphanumériques",
     *     groups={"all"})
     */
    private $name;


    /**
     * @ORM\Column(type="integer")
     */
    private ?int $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkImage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getLinkImage(): ?string
    {
        return $this->linkImage;
    }

    public function setLinkImage(?string $linkImage): self
    {
        $this->linkImage = $linkImage;

        return $this;
    }
}
