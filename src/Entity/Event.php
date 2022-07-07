<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;


/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{

    public const NOM="NOM";
    public const DESCRIPTION="DESCRIPTION";
    public const MAX_NB_PARTICIPANT="MAX NB PARTICIPANT";
    public const DATE_DEBUT="DATE DEBUT";
    public const DATE_FIN="DATE FIN";
    public const SAISON="SAISON";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"export_event_subscription","event"})
     * @SerializedName(self::NOM)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("event")
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * @Groups("event")
     */
    private $description;

    /**
     * @Assert\PositiveOrZero(message="Le montant du prix ne peut pas être négatif")
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("event")
     */
    private $maximumNumberOfParticipants;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("event")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("event")
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=55, nullable=true)
     * @Groups("event")
     */
    private $season;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("event")
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
     * @ORM\OneToMany(targetEntity=EventRate::class, mappedBy="event", orphanRemoval=true)
     */
    private Collection $eventRates;

    /**
     * @ORM\OneToMany(targetEntity=EventOption::class, mappedBy="event", orphanRemoval=true)
     */
    private Collection $eventOptions;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $allowVisitors;

    /**
     * @ORM\OneToMany(targetEntity=EventSubscription::class, mappedBy="event", orphanRemoval=true)
     */
    private $eventSubscriptions;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->eventRates = new ArrayCollection();
        $this->eventOptions = new ArrayCollection();
        $this->eventSubscriptions = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
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
     * @return Collection<int, EventRate>
     */
    public function getEventRates(): Collection
    {
        return $this->eventRates;
    }

    public function addEventRate(EventRate $eventRate): self
    {
        if (!$this->eventRates->contains($eventRate)) {
            $this->eventRates[] = $eventRate;
            $eventRate->setEvent($this);
        }

        return $this;
    }

    public function removeEventRate(EventRate $eventRate): self
    {
        if ($this->eventRates->removeElement($eventRate)) {
            // set the owning side to null (unless already changed)
            if ($eventRate->getEvent() === $this) {
                $eventRate->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function AllowVisitors(): bool
    {
        return $this->allowVisitors;
    }

    /**
     * @param bool $allowVisitors
     */
    public function setAllowVisitors(bool $allowVisitors): void
    {
        $this->allowVisitors = $allowVisitors;
    }

    /**
     * @return Collection<int, EventOption>
     */
    public function getEventOptions(): Collection
    {
        return $this->eventOptions;
    }

    public function addEventOption(EventOption $eventOption): self
    {
        if (!$this->eventOptions->contains($eventOption)) {
            $this->eventOptions[] = $eventOption;
            $eventOption->setEvent($this);
        }

        return $this;
    }

    public function removeEventOption(EventOption $eventOption): self
    {
        if ($this->eventOptions->removeElement($eventOption)) {
            // set the owning side to null (unless already changed)
            if ($eventOption->getEvent() === $this) {
                $eventOption->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EventSubscription>
     */
    public function getEventSubscriptions(): Collection
    {
        return $this->eventSubscriptions;
    }

    public function addEventSubscription(EventSubscription $eventSubscription): self
    {
        if (!$this->eventSubscriptions->contains($eventSubscription)) {
            $this->eventSubscriptions[] = $eventSubscription;
            $eventSubscription->setEvent($this);
        }

        return $this;
    }

    public function removeEventSubscription(EventSubscription $eventSubscription): self
    {
        if ($this->eventSubscriptions->removeElement($eventSubscription)) {
            // set the owning side to null (unless already changed)
            if ($eventSubscription->getEvent() === $this) {
                $eventSubscription->setEvent(null);
            }
        }

        return $this;
    }
}
