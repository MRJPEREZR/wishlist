<?php

namespace App\Entity;

use App\Enum\UserRole;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "Username cannot be empty.")]
    #[Assert\Length(min: 3, max: 50, minMessage: "Username must be at least {{ limit }} characters long.")]
    private ?string $userName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Name cannot be empty.")]
    #[Assert\Length(min: 2, max: 100, minMessage: "Name must be at least {{ limit }} characters long.")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Surname cannot be empty.")]
    #[Assert\Length(min: 2, max: 100, minMessage: "Surname must be at least {{ limit }} characters long.")]
    private ?string $surname = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "Email cannot be empty.")]
    #[Assert\Email(message: "Invalid email format.")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Password cannot be empty.")]
    #[Assert\Length(min: 8, minMessage: "Password must be at least {{ limit }} characters long.")]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    #[Assert\NotNull(message: "Blocked status cannot be null.")]
    private ?bool $isBlocked = false;

    #[ORM\Column(enumType: UserRole::class)]
    #[Assert\Choice(
        callback: [UserRole::class, 'cases'],
        message: "Invalid role value. Allowed values: {{ choices }}."
    )]
    private UserRole $role = UserRole::USER;

    #[ORM\Column]
    #[Assert\NotNull(message: "CreatedAt must be set.")]
    #[Assert\Type(type: \DateTimeImmutable::class, message: "CreatedAt must be a valid DateTimeImmutable instance.")]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName): static
    {
        $this->userName = $userName;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): static
    {
        $this->surname = $surname;
        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function isBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(?bool $isBlocked): static
    {
        $this->isBlocked = $isBlocked;
        return $this;
    }

    public function getRole(): ?UserRole
    {
        return $this->role;
    }

    public function setRole(UserRole $role): static
    {
        $this->role = $role;
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

    //Methods neeeded to be implemented here because of the extension of UserInterface, PasswordAuthenticatedUserInterface
    public function getRoles(): array
    {
        return ['ROLE_' . strtoupper($this->role)]; // Assuming UserRole is an enum with string values
    }

    public function getUserIdentifier(): string
    {
        return $this->email; // Or another unique identifier like username
    }

    public function eraseCredentials(): void
    {
        // If storing temporary sensitive data, clear it here
    } 
}
