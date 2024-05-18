<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class RecipeDifficulty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $difficultyName = null;

    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: "difficulty")]
    private Collection $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDifficultyName(): ?string
    {
        return $this->difficultyName;
    }

    public function setDifficultyName(?string $difficultyName): void
    {
        $this->difficultyName = $difficultyName;
    }

    public function getRecipes(): array
    {
        return $this->recipes;
    }

    public function setRecipes(array $recipes): void
    {
        $this->recipes = $recipes;
    }
}