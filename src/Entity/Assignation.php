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

    #[ORM\ManyToOne(targetEntity: Draw::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Draw $draw = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_giver = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_receiver = null;

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

    public function getDraw(): ?Draw
    {
        return $this->draw;
    }

    public function setDraw(?Draw $draw): self
    {
        $this->draw = $draw;

        return $this;
    }

    public function getUserGiver(): ?User
    {
        return $this->user_giver;
    }

    public function setUserGiver(?User $user_giver): self
    {
        $this->user_giver = $user_giver;

        return $this;
    }

    public function getUserReceiver(): ?User
    {
        return $this->user_receiver;
    }

    public function setUserReceiver(?User $user_receiver): self
    {
        $this->user_receiver = $user_receiver;

        return $this;
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
