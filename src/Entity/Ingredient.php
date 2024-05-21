<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\RecipeHasIngredient;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $IngredientName = null;

    #[ORM\OneToMany(targetEntity: RecipeHasIngredient::class, mappedBy: "ingredient")]
    private Collection $recipes;

    /**
     * @var Collection<int, RecipeHasIngredient>
     */
    #[ORM\OneToMany(targetEntity: RecipeHasIngredient::class, mappedBy: 'Ingredient')]
    private Collection $recipeHasIngredients;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    private ?Unit $Unit = null;


    public function __construct()
    {
        $this->recipes = new ArrayCollection();
        $this->recipeHasIngredients = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIngredientName(): ?string
    {
        return $this->IngredientName;
    }

    public function setIngredientName(string $IngredientName): static
    {
        $this->IngredientName = $IngredientName;

        return $this;
    }

    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function setRecipes(array $recipes): void
    {
        $this->recipes = $recipes;

}

    /**
     * @return Collection<int, RecipeHasIngredient>
     */
    public function getRecipeHasIngredients(): Collection
    {
        return $this->recipeHasIngredients;
    }

    public function addRecipeHasIngredient(RecipeHasIngredient $recipeHasIngredient): static
    {
        if (!$this->recipeHasIngredients->contains($recipeHasIngredient)) {
            $this->recipeHasIngredients->add($recipeHasIngredient);
            $recipeHasIngredient->setIngredient($this);
        }

        return $this;
    }

    public function removeRecipeHasIngredient(RecipeHasIngredient $recipeHasIngredient): static
    {
        if ($this->recipeHasIngredients->removeElement($recipeHasIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeHasIngredient->getIngredient() === $this) {
                $recipeHasIngredient->setIngredient(null);
            }
        }

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->Unit;
    }

    public function setUnit(?Unit $Unit): static
    {
        $this->Unit = $Unit;

        return $this;
    }
}