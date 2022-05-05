<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Assert\PositiveOrZero(message="Le montant du prix ne peut pas être négatif")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maximumNumberOfParticipants;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=55, nullable=true)
     */
    private $season;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $registrationOpenDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $registrationDeadline;

    /**
     * @ORM\ManyToMany(targetEntity=Member::class, inversedBy="events")
     */
    private $participants;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkImage;

    /**
     * @ORM\ManyToMany(targetEntity=Rate::class, mappedBy="event")
     */
    private $rates;


    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->rates = new ArrayCollection();
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getSeason(): ?string
    {
        return $this->season;
    }

    public function setSeason(?string $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getRegistrationOpenDate(): ?DateTimeInterface
    {
        return $this->registrationOpenDate;
    }

    public function setRegistrationOpenDate(?DateTimeInterface $registrationOpenDate): self
    {
        $this->registrationOpenDate = $registrationOpenDate;

        return $this;
    }

    public function getRegistrationDeadline(): ?DateTimeInterface
    {
        return $this->registrationDeadline;
    }

    public function setRegistrationDeadline(?DateTimeInterface $registrationDeadline): self
    {
        $this->registrationDeadline = $registrationDeadline;

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

    /**
     * @return Collection<int, Rate>
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(Rate $rate): self
    {
        if (!$this->rates->contains($rate)) {
            $this->rates[] = $rate;
            $rate->addEvent($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rates->removeElement($rate)) {
            $rate->removeEvent($this);
        }

        return $this;
    }
}
