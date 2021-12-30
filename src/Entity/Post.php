<?php

namespace App\Entity;

use App\Repository\PostRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 55,
     *      minMessage = "Le titre ne doit pas faire moins de {{ limit }} caractères",
     *      maxMessage = "le titre ne doit pas dépasser {{ limit }} caractères"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
    @Assert\Length(
     *      min = 2,
     *      max = 55,
     *      minMessage = "Le type d'article ne doit pas faire moins de {{ limit }} caractères",
     *      maxMessage = "le type d'article ne doit pas dépasser {{ limit }} caractères"
     * ).
     *
     * @ORM\Column(type="string", length=55)
     */
    private $type;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "La description ne doit pas faire moins de {{ limit }} caractères",
     *      maxMessage = "la description ne doit pas dépasser {{ limit }} caractères"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @Assert\Type("\DateTimeInterface")
     * @ORM\Column(type="datetime")
     */
    private $publicationDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getPublicationDate(): DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }
}
