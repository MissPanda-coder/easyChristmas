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

    #[ORM\Column]
    private ?int $drawyear = null;

    #[ORM\ManyToOne(inversedBy: 'drawsOrganized')]
    private ?User $organizer = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'drawsParticipated')]
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

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): static
    {
        $this->organizer = $organizer;

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

    public function addExclusions(Exclusion $exclusions ): static
    {
        if (!$this->exclusions ->contains($exclusions )) {
            $this->exclusions ->add($exclusions );
        }

        return $this;
    }

    public function removeExclusions(Exclusion $exclusions) : static
    {
        $this->exclusions ->removeElement($exclusions) ;

        return $this;
    }

}