<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    public ?string $email = null;

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

    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: "user")]
    private Collection $recipes;

    #[ORM\OneToMany(targetEntity: Draw::class, mappedBy: "organizer")]
    private Collection $drawsOrganized;

    #[ORM\ManyToMany(targetEntity: Draw::class, inversedBy: "participants")]
    #[ORM\JoinTable(name: "draw_has_user")]
    private Collection $drawsParticipated;

    #[ORM\OneToMany(targetEntity: Exclusion::class, mappedBy: "userParticipant")]
    private Collection $usersCanNotOffer;

    #[ORM\OneToMany(targetEntity: Exclusion::class, mappedBy: "userExcluded")]
    private Collection $usersCanNotBeOffered;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
        $this->drawsOrganized = new ArrayCollection();
        $this->drawsParticipated = new ArrayCollection();
        $this->usersCanNotOffer = new ArrayCollection();
        $this->usersCanNotBeOffered = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * This functions is used to get the email the user.
     *
     * @return string|null The email of the user
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email the email of the user
     * @return $this
     */
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

    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function setRecipes(Collection $recipes): void
    {
        $this->recipes = $recipes;
    }

    public function getDrawsOrganized(): Collection
    {
        return $this->drawsOrganized;
    }

    public function setDrawsOrganized(Collection $drawsOrganized): void
    {
        $this->drawsOrganized = $drawsOrganized;
    }

    public function getDrawsParticipated(): Collection
    {
        return $this->drawsParticipated;
    }

    public function setDrawsParticipated(Collection $drawsParticipated): void
    {
        $this->drawsParticipated = $drawsParticipated;
    }

    public function getUsersCanNotOffer(): Collection
    {
        return $this->usersCanNotOffer;
    }

    public function setUsersCanNotOffer(Collection $usersCanNotOffer): void
    {
        $this->usersCanNotOffer = $usersCanNotOffer;
    }

    public function getUsersCanNotBeOffered(): Collection
    {
        return $this->usersCanNotBeOffered;
    }

    public function setUsersCanNotBeOffered(Collection $usersCanNotBeOffered): void
    {
        $this->usersCanNotBeOffered = $usersCanNotBeOffered;
    }
}
