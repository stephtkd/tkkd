<?php

namespace App\Entity;

use App\Repository\EventOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventOptionRepository::class)
 */
class EventOption
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
    private string $name;

    /**
     * @ORM\Column(type="string", length=3000, nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="float")
     */
    private float $amount;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="eventOptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private Event $event;

    /**
     * @ORM\ManyToMany(targetEntity=EventSubscription::class, mappedBy="eventOptions")
     * @ORM\JoinTable(name="event_subscription_event_option")
     */
    private Collection $eventSubscriptions;

    public function __construct()
    {
        $this->eventSubscriptions = new ArrayCollection();
    }

    public function __toString() {
        return $this->getName();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;

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
            $eventSubscription->addEventOption($this);
        }

        return $this;
    }

    public function removeEventSubscription(EventSubscription $eventSubscription): self
    {
        if ($this->eventSubscriptions->removeElement($eventSubscription)) {
            $eventSubscription->removeEventOption($this);
        }

        return $this;
    }
}
