<?php

namespace App\Entity;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\RecipeStep;
use App\Entity\RecipeCategory;
use App\Entity\RecipeDifficulty;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\RecipeHasIngredient;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $User = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?RecipeCategory $recipeCategory = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?RecipeDifficulty $recipeDifficulty = null;

    /**
     * @var Collection<int, RecipeStep>
     */
    #[ORM\OneToMany(targetEntity: RecipeStep::class, mappedBy: 'recipe')]
    private Collection $recipeStep;

    /**
     * @var Collection<int, RecipeHasIngredient>
     */
    #[ORM\OneToMany(targetEntity: RecipeHasIngredient::class, mappedBy: 'recipe')]
    private Collection $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->User;
    }

    public function setUser(?user $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getRecipeCategory(): ?RecipeCategory
    {
        return $this->recipeCategory;
    }

    public function setRecipeCategory(?RecipeCategory $recipeCategory): static
    {
        $this->recipeCategory = $recipeCategory;

        return $this;
    }

    public function getRecipeDifficulty(): ?RecipeDifficulty
    {
        return $this->recipeDifficulty;
    }

    public function setRecipeDifficulty(?RecipeDifficulty $recipeDifficulty): static
    {
        $this->recipeDifficulty = $recipeDifficulty;

        return $this;
    }

    /**
     * @return Collection<int, RecipeStep>
     */
    public function getRecipeStep(): Collection
    {
        return $this->recipeStep;
    }

    public function addRecipeStep(RecipeStep $recipeStep): static
    {
        if (!$this->recipeStep->contains($recipeStep)) {
            $this->recipeStep->add($recipeStep);
            $recipeStep->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeStep(RecipeStep $recipeStep): static
    {
        if ($this->recipeStep->removeElement($recipeStep)) {
            // set the owning side to null (unless already changed)
            if ($recipeStep->getRecipe() === $this) {
                $recipeStep->setRecipe(null);
            }
        }

        return $this;
    }

    /**
 * Get the collection of RecipeHasIngredient
 * @return Collection<int, RecipeHasIngredient>
 */
public function getIngredients(): Collection
{
    return $this->ingredients;
}

/**
 * Add an ingredient to the recipe
 * @param RecipeHasIngredient $ingredient
 * @return self
 */
public function addIngredient(RecipeHasIngredient $ingredient): self
{
    if (!$this->ingredients->contains($ingredient)) {
        $this->ingredients[] = $ingredient;
        $ingredient->setRecipe($this);
    }

    return $this;
}

/**
 * Remove an ingredient from the recipe
 * @param RecipeHasIngredient $ingredient
 * @return self
 */
public function removeIngredient(RecipeHasIngredient $ingredient): self
{
    if ($this->ingredients->removeElement($ingredient)) {
        // Set the owning side to null (unless it was already changed)
        if ($ingredient->getRecipe() === $this) {
            $ingredient->setRecipe(null);
        }
    }

    return $this;
}


    /**
     * Get the value of photo
     */ 
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set the value of photo
     *
     * @return  self
     */ 
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get the value of isActive
     *
     * @return ?bool
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * Set the value of isActive
     *
     * @param ?bool $isActive
     *
     * @return self
     */
    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}