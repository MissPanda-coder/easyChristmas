<?php

namespace App\Entity;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Recipestep;
use App\Entity\Recipecategory;
use App\Entity\Recipedifficulty;
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

    #[ORM\Column(length: 255, nullable: false)]
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
    private ?Recipecategory $recipecategory = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?Recipedifficulty $recipedifficulty = null;

    /**
     * @var Collection<int, Recipestep>
     */
    #[ORM\OneToMany(targetEntity: Recipestep::class, mappedBy: 'recipe', cascade: ['persist'])]
    private Collection $recipestep;

    #[ORM\OneToMany(targetEntity: RecipeHasIngredient::class, mappedBy: 'recipe', cascade: ['persist'])]
    private Collection $ingredients;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;


    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->recipestep = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
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

    public function getRecipecategory(): ?Recipecategory
    {
        return $this->recipecategory;
    }

    public function setRecipecategory(?Recipecategory $recipecategory): static
    {
        $this->recipecategory = $recipecategory;

        return $this;
    }

    public function getRecipedifficulty(): ?Recipedifficulty
    {
        return $this->recipedifficulty;
    }

    public function setRecipedifficulty(?Recipedifficulty $recipedifficulty): static
    {
        $this->recipedifficulty = $recipedifficulty;

        return $this;
    }

    /**
     * @return Collection<int, Recipestep>
     */
    public function getRecipestep(): Collection
    {
        return $this->recipestep;
    }

    public function addRecipestep(Recipestep $recipestep): static
    {
        if (!$this->recipestep->contains($recipestep)) {
            $this->recipestep->add($recipestep);
            $recipestep->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipestep(Recipestep $recipestep): static
    {
        if ($this->recipestep->removeElement($recipestep)) {
            // set the owning side to null (unless already changed)
            if ($recipestep->getRecipe() === $this) {
                $recipestep->setRecipe(null);
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}