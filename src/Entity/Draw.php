<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Draw
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $drawDate = null;

    #[ORM\Column]
    private ?int $drawYear = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "drawsOrganized")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $organizer = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: "drawsParticipated")]
    private Collection $participants;

    #[ORM\OneToMany(targetEntity: Exclusion::class, mappedBy: "draw")]
    private Collection $exclusions;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->exclusions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDrawDate(): ?\DateTime
    {
        return $this->drawDate;
    }

    public function setDrawDate(?\DateTime $drawDate): void
    {
        $this->drawDate = $drawDate;
    }

    public function getDrawYear(): ?int
    {
        return $this->drawYear;
    }

    public function setDrawYear(?int $drawYear): void
    {
        $this->drawYear = $drawYear;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): void
    {
        $this->organizer = $organizer;
    }

    public function getParticipants(): array
    {
        return $this->participants;
    }

    public function setParticipants(array $participants): void
    {
        $this->participants = $participants;
    }

    public function getExclusions(): array
    {
        return $this->exclusions;
    }

    public function setExclusions(array $exclusions): void
    {
        $this->exclusions = $exclusions;
    }
}
