<?php
// src/Entity/Tag.php
namespace App\Entity;

class Tug
{
    // private $name;

    private Member $member;

    // public function getName(): string
    // {
    //     return $this->name;
    // }

    // public function setName(string $name): void
    // {
    //     $this->name = $name;
    // }

    public function getMember(): Member
    {
        return $this->member;
    }

    public function setMember(Member $member): self
    {
        $this->member = $member;

        return $this;
    }
}