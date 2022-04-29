<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MembershipRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=MembershipRepository::class)
 */
class Membership
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $seasonYear;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $suscriptionDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $membershipUpToDate;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="memberships")
     * @ORM\JoinColumn(nullable=false)
     */
    private $member;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $membershipState;

    /**
     * @ORM\ManyToOne(targetEntity=MembershipRate::class, inversedBy="memberships")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membershipRate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeasonYear(): ?int
    {
        return $this->seasonYear;
    }

    public function setSeasonYear(int $seasonYear): self
    {
        $this->seasonYear = $seasonYear;

        return $this;
    }

    public function getSuscriptionDate(): ?\DateTimeInterface
    {
        return $this->suscriptionDate;
    }

    public function setSuscriptionDate(?\DateTimeInterface $suscriptionDate): self
    {
        $this->suscriptionDate = $suscriptionDate;

        return $this;
    }

    public function getMembershipUpToDate(): ?bool
    {
        return $this->membershipUpToDate;
    }

    public function setMembershipUpToDate(bool $membershipUpToDate): self
    {
        $this->membershipUpToDate = $membershipUpToDate;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getMembershipState(): ?string
    {
        return $this->membershipState;
    }

    public function setMembershipState(string $membershipState): self
    {
        $this->membershipState = $membershipState;

        return $this;
    }

    public function getMembershipRate(): ?MembershipRate
    {
        return $this->membershipRate;
    }

    public function setMembershipRate(?MembershipRate $membershipRate): self
    {
        $this->membershipRate = $membershipRate;

        return $this;
    }
}
