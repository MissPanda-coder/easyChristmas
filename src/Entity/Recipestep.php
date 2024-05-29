<?php

namespace App\Entity;

use App\Repository\RecipestepRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipestepRepository::class)]
class Recipestep
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $stepnumber = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'Recipestep')]
    private ?Recipe $recipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStepnumber(): ?int
    {
        return $this->stepnumber;
    }

    public function setStepnumber(int $stepnumber): static
    {
        $this->stepnumber = $stepnumber;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }
}
