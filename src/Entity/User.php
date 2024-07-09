<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

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

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    #[Assert\Regex(
        pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/",
        message: "Le mot de passe doit contenir au moins 6 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial."
    )]
    #[Assert\NotBlank(message: 'Veuillez renseigner un mot de passe.')]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean', name: "is_verified")]
    private bool $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(
        pattern: "/\.(jpg|jpeg|png|webp)$/i",
        message: "Le nom du fichier doit se terminer par .jpg, .jpeg, .webp ou .png."
    )]
    private ?string $photo = null;

    #[ORM\Column(length: 150, unique: true)]
    #[Assert\Length(min: 2, minMessage: 'Le nom d\'utilisateur doit faire au moins 2 caractères.')]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[Assert\Regex(
        pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{6,}$/",
        message: "Le mot de passe doit contenir au moins 6 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial."
    )]
    private $newPassword;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $verificationToken = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $verificationTokenExpiresAt = null;


    #[ORM\OneToMany(targetEntity: Exclusion::class, mappedBy: "userparticipant", cascade: ["remove"])]
    private Collection $usersCanNotOffer;

    #[ORM\OneToMany(targetEntity: Exclusion::class, mappedBy: "userexcluded", cascade: ["remove"])]
    private Collection $usersCanNotBeOffered;

    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: 'user')]
    private Collection $recipes;

    #[ORM\ManyToMany(targetEntity: Draw::class, mappedBy: 'participants')]
    private Collection $drawsParticipated;

    #[ORM\ManyToMany(targetEntity: Wishes::class, mappedBy: 'users')]
    private Collection $wishes;


    public function __construct()
    {
        $this->usersCanNotOffer = new ArrayCollection();
        $this->usersCanNotBeOffered = new ArrayCollection();
        $this->recipes = new ArrayCollection();
        $this->drawsParticipated = new ArrayCollection();
        $this->wishes = new ArrayCollection();
    
    }

    /**
     * Get the value of id
     */ 
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of email
     *
     * @return ?string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param ?string $email
     *
     * @return self
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Get the value of roles
     */     
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Get the value of isVerified
     */ 
    public function getIsVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * Set the value of isVerified
     *
     * @return  self
     */ 
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    /**
     * Get the value of photo
     */ 
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * Set the value of photo
     *
     * @return  self
     */ 
    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    public function getPhotoPath(): string
    {
        return $this->photo ? '/uploads/photos/' . $this->photo : '/images/default-avatar.png';
    }

    /**
     * Get the value of username
     */ 
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get the value of firstname
     */ 
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */ 
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * Get the value of lastname
     */ 
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */ 
    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Get the value of newPassword
     */ 
    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    /**
     * Set the value of newPassword
     *
     * @return  self
     */ 
    public function setNewPassword(?string $newPassword): self
    {
        $this->newPassword = $newPassword;
        return $this;
    }

    /**
     * Get the value of usersCanNotOffer
     */ 
    public function getUsersCanNotOffer(): Collection
    {
        return $this->usersCanNotOffer;
    }

    /**
     * Set the value of usersCanNotOffer
     *
     * @return  self
     */ 
    public function setUsersCanNotOffer(Collection $usersCanNotOffer): self
    {
        $this->usersCanNotOffer = $usersCanNotOffer;
        return $this;
    }

    /**
     * Get the value of usersCanNotBeOffered
     */ 
    public function getUsersCanNotBeOffered(): Collection
    {
        return $this->usersCanNotBeOffered;
    }

    /**
     * Set the value of usersCanNotBeOffered
     *
     * @return  self
     */ 
    public function setUsersCanNotBeOffered(Collection $usersCanNotBeOffered): self
    {
        $this->usersCanNotBeOffered = $usersCanNotBeOffered;
        return $this;
    }

    /**
     * Get the value of recipes
     */ 
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    /**
     * Set the value of recipes
     *
     * @return  self
     */ 
    public function setRecipes(Collection $recipes): self
    {
        $this->recipes = $recipes;
        return $this;
    }

    /**
     * Get the value of drawsParticipated
     */ 
    public function getDrawsParticipated(): Collection
    {
        return $this->drawsParticipated;
    }

    /**
     * Set the value of drawsParticipated
     *
     * @return  self
     */ 
    public function setDrawsParticipated(Collection $drawsParticipated): self
    {
        $this->drawsParticipated = $drawsParticipated;
        return $this;
    }

    /**
     * Get the value of wishes
     */ 
    public function getWishes(): Collection
    {
        return $this->wishes;
    }

    /**
     * Set the value of wishes
     *
     * @return  self
     */ 
    public function setWishes(Collection $wishes): self
    {
        $this->wishes = $wishes;
        return $this;
    }

    /**
     * Get the value of password
     *
     * @return ?string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->newPassword = null;
    }

    /**
     * Get the value of verificationToken
     */ 
    public function getVerificationToken()
    {
        return $this->verificationToken;
    }

    /**
     * Set the value of verificationToken
     *
     * @return  self
     */ 
    public function setVerificationToken($verificationToken)
    {
        $this->verificationToken = $verificationToken;

        return $this;
    }

    /**
     * Get the value of verificationTokenExpiresAt
     */ 
    public function getVerificationTokenExpiresAt()
    {
        return $this->verificationTokenExpiresAt;
    }

    /**
     * Set the value of verificationTokenExpiresAt
     *
     * @return  self
     */ 
    public function setVerificationTokenExpiresAt($verificationTokenExpiresAt)
    {
        $this->verificationTokenExpiresAt = $verificationTokenExpiresAt;

        return $this;
    }


}
