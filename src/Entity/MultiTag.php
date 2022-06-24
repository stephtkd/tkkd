<?php

namespace App\Entity;

use App\Repository\MultiTagRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MultiTagRepository::class)
 */
class MultiTag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class, inversedBy="multiTags")
     */
    private $Tag;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->Tag;
    }

    public function setTag(?Tag $Tag): self
    {
        $this->Tag = $Tag;

        return $this;
    }
}
