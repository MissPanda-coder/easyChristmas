<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 250)]
    private ?string $title = null;

    #[ORM\Column(type: "text")]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "recipes")]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: RecipeDifficulty::class, inversedBy: "recipes", cascade: ["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecipeDifficulty $difficulty = null;

    #[ORM\ManyToOne(targetEntity: RecipeCategory::class, inversedBy: "recipes", cascade: ["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecipeCategory $category = null;

    #[ORM\OneToMany(targetEntity: RecipeStep::class, mappedBy: "recipe")]
    private Collection $steps;

    #[ORM\OneToMany(targetEntity: RecipeHasIngredient::class, mappedBy: "recipe")]
    private Collection $ingredients;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): void
    {
        $this->duration = $duration;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getDifficulty(): ?RecipeDifficulty
    {
        return $this->difficulty;
    }

    public function setDifficulty(?RecipeDifficulty $difficulty): void
    {
        $this->difficulty = $difficulty;
    }

    public function getCategory(): ?RecipeCategory
    {
        return $this->category;
    }

    public function setCategory(?RecipeCategory $category): void
    {
        $this->category = $category;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function addStep(RecipeStep $step): void
    {
        $this->steps[] = $step;
    }

    public function removeStep(RecipeStep $step): void
    {
        unset(
            $this->steps[
                array_search(
                    $step->getId(),
                    array_map(
                        function (RecipeStep $step) {
                            return $step->getId();
                        }, $this->steps
                    )
                )
            ]
        );
    }

    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    public function setIngredients(array $ingredients): void
    {
        $this->ingredients = $ingredients;
    }
}
