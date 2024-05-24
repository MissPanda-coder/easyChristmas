<?php

namespace App\Entity;

use App\Entity\Draw;
use App\Entity\Profile;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    private $plainPassword;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Profile $profile = null;

    /**
     * @var Collection<int, Recipe>
     */
    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: 'User')]
    private Collection $recipes;

    /**
     * @var Collection<int, Draw>
     */
    #[ORM\OneToMany(targetEntity: Draw::class, mappedBy: 'organizer')]
    private Collection $drawsOrganized;

    /**
     * @var Collection<int, Draw>
     */
    #[ORM\ManyToMany(targetEntity: Draw::class, mappedBy: 'participants')]
    #[ORM\JoinTable(name: "draw_has_user")]
    private Collection $drawsParticipated;

    /**
     * @var Collection<int, Role>
     */
    #[ORM\ManyToMany(targetEntity: Role::class, mappedBy: 'User')]
    #[ORM\JoinTable(name: "user_has_role")]
    private Collection $role;

    /**
     * @var Collection<int, Wishes>
     */
    #[ORM\ManyToMany(targetEntity: Wishes::class, mappedBy: 'User')]
    #[ORM\JoinTable(name: "user_has_wishes")]
    private Collection $wishes;

   
    public function __construct()
    {
        $this->drawsOrganized = new ArrayCollection();
        $this->drawsParticipated = new ArrayCollection();
        $this->role = new ArrayCollection();
        $this->wishes = new ArrayCollection();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }
    
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        
        return $this;
    }



    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setUser($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getUser() === $this) {
                $recipe->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Draw>
     */
    public function getDrawsOrganized(): Collection
    {
        return $this->drawsOrganized;
    }

    public function addDrawsOrganized(Draw $drawsOrganized): static
    {
        if (!$this->drawsOrganized->contains($drawsOrganized)) {
            $this->drawsOrganized->add($drawsOrganized);
            $drawsOrganized->setOrganizer($this);
        }

        return $this;
    }

    public function removeDrawsOrganized(Draw $drawsOrganized): static
    {
        if ($this->drawsOrganized->removeElement($drawsOrganized)) {
            // set the owning side to null (unless already changed)
            if ($drawsOrganized->getOrganizer() === $this) {
                $drawsOrganized->setOrganizer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Draw>
     */
    public function getDrawsParticipated(): Collection
    {
        return $this->drawsParticipated;
    }

    public function addDrawsParticipated(Draw $drawsParticipated): static
    {
        if (!$this->drawsParticipated->contains($drawsParticipated)) {
            $this->drawsParticipated->add($drawsParticipated);
            $drawsParticipated->addParticipant($this);
        }

        return $this;
    }

    public function removeDrawsParticipated(Draw $drawsParticipated): static
    {
        if ($this->drawsParticipated->removeElement($drawsParticipated)) {
            $drawsParticipated->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function addRole(Role $role): static
    {
        if (!$this->role->contains($role)) {
            $this->role->add($role);
            $role->addUser($this);
        }

        return $this;
    }

    public function removeRole(Role $role): static
    {
        if ($this->role->removeElement($role)) {
            $role->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Wishes>
     */
    public function getWishes(): Collection
    {
        return $this->wishes;
    }

    public function addWish(Wishes $wish): static
    {
        if (!$this->wishes->contains($wish)) {
            $this->wishes->add($wish);
            $wish->addUser($this);
        }

        return $this;
    }

    public function removeWish(Wishes $wish): static
    {
        if ($this->wishes->removeElement($wish)) {
            $wish->removeUser($this);
        }

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        
    // Set the owning side of the relation if necessary
    if ($profile !== null && $profile->getUser() !== $this) {
        $profile->setUser($this);
    }
    $this->profile = $profile;
    return $this;
}

    /**
     * Get the value of isVerified
     */
    public function getIsVerified()
    {
        return $this->isVerified;
    }

    /**
     * Set the value of isVerified
     */
    public function setIsVerified($isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    }

   
     
