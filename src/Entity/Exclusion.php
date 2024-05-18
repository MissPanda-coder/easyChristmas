<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Exclusion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Draw::class, inversedBy: "exclusions")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Draw $draw = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "usersCanNotOffer")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userParticipant = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "usersCanNotBeOffered")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userExcluded = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDraw(): ?Draw
    {
        return $this->draw;
    }

    public function setDraw(?Draw $draw): void
    {
        $this->draw = $draw;
    }

    public function getUserParticipant(): ?User
    {
        return $this->userParticipant;
    }

    public function setUserParticipant(?User $userParticipant): void
    {
        $this->userParticipant = $userParticipant;
    }

    public function getUserExcluded(): ?User
    {
        return $this->userExcluded;
    }

    public function setUserExcluded(?User $userExcluded): void
    {
        $this->userExcluded = $userExcluded;
    }
}
