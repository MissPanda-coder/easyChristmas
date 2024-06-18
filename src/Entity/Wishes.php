<?php

namespace App\Entity;

use App\Repository\WishesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WishesRepository::class)]
class Wishes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $wishesyear = null;

    #[ORM\Column(length: 50)]
    private ?string $wishname = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Assignation::class, mappedBy: 'wishes')]
    private Collection $assignations;

    public function __construct()
    {
        $this->assignations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWishesyear(): ?int
    {
        return $this->wishesyear;
    }

    public function setWishesyear(int $wishesyear): self
    {
        $this->wishesyear = $wishesyear;

        return $this;
    }

    public function getWishname(): ?string
    {
        return $this->wishname;
    }

    public function setWishname(?string $wishname): self
    {
        $this->wishname = $wishname;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAssignations(): Collection
    {
        return $this->assignations;
    }

    public function addAssignation(Assignation $assignation): self
    {
        if (!$this->assignations->contains($assignation)) {
            $this->assignations[] = $assignation;
            $assignation->addWish($this);
        }

        return $this;
    }

    public function removeAssignation(Assignation $assignation): self
    {
        if ($this->assignations->removeElement($assignation)) {
            $assignation->removeWish($this);
        }

        return $this;
    }
}
