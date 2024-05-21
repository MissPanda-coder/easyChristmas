<?php

namespace App\Entity;

use App\Repository\AssignationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssignationRepository::class)]
class Assignation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Wishes>
     */
    #[ORM\ManyToMany(targetEntity: Wishes::class, inversedBy: 'assignations')]
    #[ORM\JoinTable(name: "assignation_has_wishes")]
    private Collection $Wishes;

    public function __construct()
    {
        $this->Wishes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Wishes>
     */
    public function getWishes(): Collection
    {
        return $this->Wishes;
    }

    public function addWish(Wishes $wish): static
    {
        if (!$this->Wishes->contains($wish)) {
            $this->Wishes->add($wish);
        }

        return $this;
    }

    public function removeWish(Wishes $wish): static
    {
        $this->Wishes->removeElement($wish);

        return $this;
    }
}
