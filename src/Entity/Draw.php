<?php

namespace App\Entity;

use App\Repository\DrawRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DrawRepository::class)]
class Draw
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $drawdate = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $drawyear = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'drawsParticipated')]
    private Collection $participants;

    #[ORM\OneToMany(targetEntity: Exclusion::class, mappedBy: "draw")]
    private Collection $exclusions;

    #[ORM\OneToMany(targetEntity: Assignation::class, mappedBy: "draw", cascade: ["persist", "remove"])]
    private Collection $assignations;

    public function __construct()
    {
        $this->drawdate = new \DateTime();
        $this->drawyear = (new \DateTime())->format('Y');        
        $this->participants = new ArrayCollection();
        $this->exclusions = new ArrayCollection();
        $this->assignations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDrawdate(): ?\DateTimeInterface
    {
        return $this->drawdate;
    }

    public function setDrawdate(\DateTimeInterface $drawdate): static
    {
        $this->drawdate = $drawdate;

        return $this;
    }

    public function getDrawyear(): ?int
    {
        return $this->drawyear;
    }

    public function setDrawyear(int $drawyear): static
    {
        $this->drawyear = $drawyear;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    /**
     * @return Collection<int, Exclusion>
     */
    public function getExclusions(): Collection
    {
        return $this->exclusions;
    }

    public function addExclusions(Exclusion $exclusions): static
    {
        if (!$this->exclusions->contains($exclusions)) {
            $this->exclusions->add($exclusions);
        }

        return $this;
    }

    public function removeExclusions(Exclusion $exclusions): static
    {
        $this->exclusions->removeElement($exclusions);

        return $this;
    }

    /**
     * Get the value of assignations
     *
     * @return Collection
     */public function getAssignations(): Collection
    {
        return $this->assignations;
    }

    public function addAssignation(Assignation $assignation): self
    {
        if (!$this->assignations->contains($assignation)) {
            $this->assignations[] = $assignation;
            $assignation->setDraw($this);
        }

        return $this;
    }

    public function removeAssignation(Assignation $assignation): self
    {
        if ($this->assignations->removeElement($assignation)) {
            // set the owning side to null (unless already changed)
            if ($assignation->getDraw() === $this) {
                $assignation->setDraw(null);
            }
        }

        return $this;
    }
}
