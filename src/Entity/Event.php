<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @Assert\PositiveOrZero(message="Le montant du prix ne peut pas être négatif")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maximumNumberOfParticipants;

    /**
     * @Assert\PositiveOrZero(message="Le montant du prix ne peut pas être négatif")
     * @ORM\Column(type="float", nullable=true)
     */
    private $adultRate;

    /**
     * @Assert\PositiveOrZero(message="Le montant du prix ne peut pas être négatif")
     * @ORM\Column(type="float", nullable=true)
     */
    private $childRate;

    /**
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $registrationDeadline;

    /**
     * @ORM\ManyToMany(targetEntity=Member::class, inversedBy="events")
     */
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMaximumNumberOfParticipants(): ?int
    {
        return $this->maximumNumberOfParticipants;
    }

    public function setMaximumNumberOfParticipants(?int $maximumNumberOfParticipants): self
    {
        $this->maximumNumberOfParticipants = $maximumNumberOfParticipants;

        return $this;
    }

    public function getAdultRate(): ?float
    {
        return $this->adultRate;
    }

    public function setAdultRate(?float $adultRate): self
    {
        $this->adultRate = $adultRate;

        return $this;
    }

    public function getChildRate(): ?float
    {
        return $this->childRate;
    }

    public function setChildRate(?float $childRate): self
    {
        $this->childRate = $childRate;

        return $this;
    }

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getRegistrationDeadline(): ?\DateTimeInterface
    {
        return $this->registrationDeadline;
    }

    public function setRegistrationDeadline(?\DateTimeInterface $registrationDeadline): self
    {
        $this->registrationDeadline = $registrationDeadline;

        return $this;
    }

    /**
     * @return Collection|Member[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Member $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(Member $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }
}