<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Json;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
{

    public const MEAN="MEAN";
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
     * @SerializedName(self::MEAN)
     */
    private string $mean;

    /**
     * @Assert\Choice({"en attente", "refusé", "ok"})
     * @ORM\Column(type="string", length=255)
     */
    private string $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $reference;

    /**
     * @ORM\Column(type="float")
     * @Groups("export_event_subscription")
     * @SerializedName(self::MEAN)
     */
    private float $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $date;

    /**
     * @ORM\Column(type="json")
     */
    private $details;

    /**
     * @ORM\OneToMany(targetEntity=EventSubscription::class, mappedBy="payment")
     */
    private Collection $eventSubscriptions;

    public function __construct()
    {
        $this->eventSubscriptions = new ArrayCollection();
    }

    public function __toString() {
        return 'Payment '.$this->getId();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMean(): ?string
    {
        return $this->mean;
    }

    public function setMean(string $mean): self
    {
        $this->mean = $mean;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function setDetails($details): self
    {
        $this->details = $details;

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
            $eventSubscription->setPayment($this);
        }

        return $this;
    }

    public function removeEventSubscription(EventSubscription $eventSubscription): self
    {
        if ($this->eventSubscriptions->removeElement($eventSubscription)) {
            // set the owning side to null (unless already changed)
            if ($eventSubscription->getPayment() === $this) {
                $eventSubscription->setPayment(null);
            }
        }

        return $this;
    }

    public function getDetail() {
        return json_encode($this->details);
    }
}
