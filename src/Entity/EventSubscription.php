<?php

namespace App\Entity;

use App\Repository\EventSubscriptionRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;


/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=EventSubscriptionRepository::class)
 */
class EventSubscription
{

    public const MEMBRE="MEMBRE";
    public const EVENEMENT="EVENEMENT";
    public const UTILISATEUR="UTILISATEUR";
    public const TARIF="TARIF";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="eventSubscriptions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("export_event_subscription")
     * @SerializedName(self::EVENEMENT)
     */
    private Event $event;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="eventSubscriptions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("export_event_subscription")
     * @SerializedName(self::MEMBRE)
     * 
     */
    private Member $member;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="eventSubscriptions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("export_event_subscription")
     * @SerializedName(self::UTILISATEUR)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=EventRate::class, inversedBy="eventSubscriptions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("export_event_subscription")
     * @SerializedName(self::TARIF)
     */
    private EventRate $eventRate;

    /**
     * @ORM\ManyToMany(targetEntity=EventOption::class, inversedBy="eventSubscriptions", cascade={"merge"}, fetch="EAGER")
     * @ORM\JoinTable(name="event_subscription_event_option")
     */
    private Collection $eventOptions;

    /**
     * @Assert\Choice({"en attente de pi??ces", "r??sili??e", "ok"})
     * @ORM\Column(type="string", length=255)
     */
    private ?string $status;

    /**
     * @ORM\ManyToOne(targetEntity=Payment::class, inversedBy="eventSubscriptions", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private Payment $payment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={"image/gif", "image/jpeg", "image/png", "image/pdf"})
     */
    private ?string $medicalCertificateName;

    /**
     * @ORM\Column(type="string", length=5000, nullable=true)
     */
    private ?string $comment;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPaid = false;

    /**
     * @ORM\ManyToOne(targetEntity=Invoice::class, inversedBy="eventSubscriptions", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private Invoice $invoice;

    public function __construct()
    {
        $this->eventOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMember(): Member
    {
        return $this->member;
    }

    public function setMember(Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEventRate(): EventRate
    {
        return $this->eventRate;
    }

    public function setEventRate(EventRate $eventRate): self
    {
        $this->eventRate = $eventRate;

        return $this;
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
        }

        return $this;
    }

    public function removeEventOption(EventOption $eventOption): self
    {
        $this->eventOptions->removeElement($eventOption);

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

    public function getPayment(): Payment
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getMedicalCertificateName(): ?string
    {
        return $this->medicalCertificateName;
    }

    public function setMedicalCertificateName(string $medicalCertificateName): self
    {
        $this->medicalCertificateName = $medicalCertificateName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getAmount() {
        $totalOption = 0;
        foreach ($this->getEventOptions() as $option) {
            $totalOption += $option->getAmount();
        }

        return $this->getEventRate()->getAmount() + $totalOption;
    }
    
    public function isIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): self
    {
        $this->isPaid = $isPaid;

        return $this;
    }


    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(Invoice $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

}
