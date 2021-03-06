<?php

namespace App\Entity;

use App\Repository\EventRateRepository;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=EventRateRepository::class)
 */
class EventRate
{

    public const NOM="NOM";
    public const MONTANT="MONTANT";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("export_event_subscription")
     * @SerializedName(self::NOM)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=3000, nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="float")
     * @Groups("export_event_subscription")
     * @SerializedName(self::MONTANT)
     */
    private float $amount;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="eventRates")
     * @ORM\JoinColumn(nullable=false)
     */
    private Event $event;

    /**
     * @ORM\OneToMany(targetEntity=EventSubscription::class, mappedBy="eventRate")
     */
    private $eventSubscriptions;

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
            $eventSubscription->setEventRate($this);
        }

        return $this;
    }

    public function removeEventSubscription(EventSubscription $eventSubscription): self
    {
        if ($this->eventSubscriptions->removeElement($eventSubscription)) {
            // set the owning side to null (unless already changed)
            if ($eventSubscription->getEventRate() === $this) {
                $eventSubscription->setEventRate(null);
            }
        }

        return $this;
    }
}
