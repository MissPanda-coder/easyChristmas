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
    private ?User $userGiver = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userReceiver = null;

    /**
     * @var Collection<int, Wishes>
     */
    #[ORM\ManyToMany(targetEntity: Wishes::class, inversedBy: 'assignations')]
    #[ORM\JoinTable(name: "assignation_has_wishes")]
    private Collection $wishes;

    public function __construct()
    {
        $this->wishes = new ArrayCollection();
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
        return $this->userGiver;
    }

    public function setUserGiver(?User $userGiver): self
    {
        $this->userGiver = $userGiver;

        return $this;
    }

    public function getUserReceiver(): ?User
    {
        return $this->userReceiver;
    }

    public function setUserReceiver(?User $userReceiver): self
    {
        $this->userReceiver = $userReceiver;

        return $this;
    }

    /**
     * @return Collection<int, Wishes>
     */
    public function getWishes(): Collection
    {
        return $this->wishes;
    }

    public function addWish(Wishes $wish): self
    {
        if (!$this->wishes->contains($wish)) {
            $this->wishes->add($wish);
        }

        return $this;
    }

    public function removeWish(Wishes $wish): self
    {
        $this->wishes->removeElement($wish);

        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            'Giver: %s, Receiver: %s',
            $this->getUserGiver() ? $this->getUserGiver()->getEmail() : 'N/A',
            $this->getUserReceiver() ? $this->getUserReceiver()->getEmail() : 'N/A'
        );
    }
}
