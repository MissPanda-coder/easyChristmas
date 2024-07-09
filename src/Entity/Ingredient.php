<?php

namespace App\Entity;

use App\Entity\Unit;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\RecipeHasIngredient;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[UniqueEntity(fields: ['ingredientname'], message: 'Cet ingrédient existe déjà.')]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $ingredientname = null;

    #[ORM\OneToMany(targetEntity: RecipeHasIngredient::class, mappedBy: "ingredient")]
    private Collection $recipes;

    
    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    private ?Unit $unit = null;


    public function __construct()
    {
        $this->recipes = new ArrayCollection();
       
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->ingredientname;
    }
    public function getIngredientname(): ?string
    {
        return $this->ingredientname;
    }

    public function setIngredientname(string $ingredientname): static
    {
        $this->ingredientname = $ingredientname;

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
    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): static
    {
        $this->unit = $unit;

        return $this;
    }
}