<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MembershipRateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @UniqueEntity("label", message="Ce tarif existe déjà")
 * @ORM\Entity(repositoryClass=MembershipRateRepository::class)
 */
class MembershipRate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @Assert\NotBlank(message="Veuillez renseigner un libellé pour le tarif")
     * @ORM\Column(type="string", length=255)
     */
    private ?string $label;

    /**
     * @Assert\NotBlank(message="Veuillez renseigner un prix pour ce tarif")
     * @Assert\PositiveOrZero(message="Le montant du prix ne peut pas être négatif")
     * @Assert\Type(type="float", message="Le prix doit être un nombre")
     * @ORM\Column(type="float")
     */
    private ?float $price;

    /**
     * @ORM\OneToMany(targetEntity=Member::class, mappedBy="membershipRate")
     */
    private $members;

    /**
     * @Assert\Positive
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maximumAge;

    /**
     * @ORM\OneToMany(targetEntity=Membership::class, mappedBy="membershipRate")
     */
    private $memberships;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->memberships = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Member[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setMembershipRate($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getMembershipRate() === $this) {
                $member->setMembershipRate(null);
            }
        }

        return $this;
    }

    public function getMaximumAge(): ?int
    {
        return $this->maximumAge;
    }

    public function setMaximumAge(?int $maximumAge): self
    {
        $this->maximumAge = $maximumAge;

        return $this;
    }

    /**
     * @return Collection|Membership[]
     */
    public function getMemberships(): Collection
    {
        return $this->memberships;
    }

    public function addMembership(Membership $membership): self
    {
        if (!$this->memberships->contains($membership)) {
            $this->memberships[] = $membership;
            $membership->setMembershipRate($this);
        }

        return $this;
    }

    public function removeMembership(Membership $membership): self
    {
        if ($this->memberships->removeElement($membership)) {
            // set the owning side to null (unless already changed)
            if ($membership->getMembershipRate() === $this) {
                $membership->setMembershipRate(null);
            }
        }

        return $this;
    }
}
