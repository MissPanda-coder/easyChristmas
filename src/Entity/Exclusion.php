<?php

namespace App\Entity;

use App\Repository\ExclusionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExclusionRepository::class)]
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
    private ?User $userparticipant = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "usersCanNotBeOffered")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userexcluded = null;

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

    public function getUserparticipant(): ?User
    {
        return $this->userparticipant;
    }

    public function setUserparticipant(?User $userparticipant): void
    {
        $this->userparticipant = $userparticipant;
    }

    public function getUserexcluded(): ?User
    {
        return $this->userexcluded;
    }

    public function setUserexcluded(?User $userexcluded): void
    {
        $this->userexcluded = $userexcluded;
    }
}
