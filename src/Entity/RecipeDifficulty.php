<?php

namespace App\Entity;

use App\Repository\RecipeDifficultyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeDifficultyRepository::class)]
class RecipeDifficulty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $difficultyName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDifficultyName(): ?string
    {
        return $this->difficultyName;
    }

    public function setDifficultyName(string $difficultyName): static
    {
        $this->difficultyName = $difficultyName;

        return $this;
    }
}
