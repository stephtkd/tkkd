<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 */
class Invoice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Collection|EventSubscription[] 
     * @ORM\OneToMany(targetEntity=EventSubscription::class, mappedBy="invoice")
     * @ORM\JoinTable(name="event_subscription_invoice")
     */
    protected Collection $eventSubscriptions;

    protected $tugs;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPaid;

    public function __construct()
    {
        $this->eventSubscriptions = new ArrayCollection();
        $this->tugs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /** @return EventSubscription[] */
    public function getEventSubscriptions(): Collection
    {
        return $this->eventSubscriptions;
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

    public function getTugs(): Collection
    {
        return $this->tugs;
    }
}
