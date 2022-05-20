<?php

namespace App\Entity;

use App\Repository\EventSubscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventSubscriptionRepository::class)
 */
class EventSubscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="eventSubscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private Event $event;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="eventSubscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private Member $member;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="eventSubscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=EventRate::class, inversedBy="eventSubscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private EventRate $eventRate;

    /**
     * @ORM\ManyToMany(targetEntity=EventOption::class, inversedBy="eventSubscriptions")
     */
    private Collection $eventOptions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $status;

    /**
     * @ORM\ManyToOne(targetEntity=Payment::class, inversedBy="eventSubscriptions", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private Payment $payment;

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
}