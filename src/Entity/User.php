<?php

namespace App\Entity;

use App\Entity\Draw;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé.')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'Ce nom d\'utilisateur est déjà pris.')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\Email(message: 'Veuillez entrer un email valide.')]
    #[Assert\NotBlank(message: 'Veuillez renseigner votre email.')]
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
    #[Assert\Regex(
        pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/",
        message: "Le mot de passe doit contenir au moins 6 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial."
    )]
    #[Assert\NotBlank(message: 'Veuillez renseigner un mot de passe.')]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean', name: "is_verified",)]
    private $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 150, nullable: true, unique: true)]
    #[Assert\Length(min: 2, minMessage: 'Le nom d\'utilisateur doit faire au moins 2 caractères.')]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[Assert\Length(min: 6, minMessage: 'Le mot de passe doit faire au moins 6 caractères.')]
    private $newPassword;
  
  
    public function getNewPassword(): ?string
    {
      return $this->newPassword;
    }
  
    public function setNewPassword(string $newPassword): self
    {
      $this->newPassword = $newPassword;
  
      return $this;
    }
    
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

    /**
     * @var Collection<int, EmailVerification>
     */
    #[ORM\OneToMany(targetEntity: EmailVerification::class, mappedBy: 'user')]
    private Collection $emailVerifications;

    public function __construct()
    {
        $this->drawsOrganized = new ArrayCollection();
        $this->drawsParticipated = new ArrayCollection();
        $this->role = new ArrayCollection();
        $this->wishes = new ArrayCollection();
        $this->emailVerifications = new ArrayCollection();
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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }
    public function getPhotoPath(): string
    {
        if ($this->photo) {
            return '/uploads/photos/' . $this->photo;
        }
        return '/images/avatars/defaultAvatar.png';
    }
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstName(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }



    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

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
            if ($recipe->getUser() === $this) {
                $recipe->setUser(null);
            }
        }

        return $this;
    }

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
            if ($drawsOrganized->getOrganizer() === $this) {
                $drawsOrganized->setOrganizer(null);
            }
        }

        return $this;
    }

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

    
    public function getIsVerified()
    {
        return $this->isVerified;
    }

    public function setIsVerified($isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, EmailVerification>
     */
    public function getEmailVerifications(): Collection
    {
        return $this->emailVerifications;
    }

    public function addEmailVerification(EmailVerification $emailVerification): static
    {
        if (!$this->emailVerifications->contains($emailVerification)) {
            $this->emailVerifications->add($emailVerification);
            $emailVerification->setUser($this);
        }

        return $this;
    }

    public function removeEmailVerification(EmailVerification $emailVerification): static
    {
        if ($this->emailVerifications->removeElement($emailVerification)) {
            // set the owning side to null (unless already changed)
            if ($emailVerification->getUser() === $this) {
                $emailVerification->setUser(null);
            }
        }

        return $this;
    }

  

}
