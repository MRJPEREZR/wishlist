<?php

namespace App\Entity;

use App\Repository\WishListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WishListRepository::class)]
class WishList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    #[Assert\NotNull(message: "User must be provided.")]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Name cannot be empty.")]
    #[Assert\Length(max: 255, maxMessage: "Name cannot be longer than 255 characters.")]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Description cannot be longer than 255 characters.")]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(type: \DateTimeInterface::class, message: "Expiration date must be a valid date.")]
    #[Assert\GreaterThan("today", message: "Expiration date must be in the future.")]
    private ?\DateTimeInterface $expirationDate = null;

    #[ORM\Column(options: ['default' => true])]
    #[Assert\NotNull(message: "isActive status must be set.")]
    #[Assert\Type(type: 'bool', message: "isActive must be a boolean value.")]
    private ?bool $isActive = true;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "View mode URL cannot be empty.")]
    #[Assert\Length(max: 255, maxMessage: "View mode URL cannot be longer than 255 characters.")]
    #[Assert\Url(message: "View mode URL must be a valid URL.")]
    private ?string $urlViewMode = null;

    #[Assert\NotBlank(message: "Edit mode URL cannot be empty.")]
    #[Assert\Length(max: 255, maxMessage: "Edit mode URL cannot be longer than 255 characters.")]
    #[Assert\Url(message: "Edit mode URL must be a valid URL.")]
    #[ORM\Column(length: 255)]
    private ?string $urlEditMode = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "CreatedAt must be set.")]
    #[Assert\Type(type: \DateTimeImmutable::class, message: "CreatedAt must be a valid DateTimeImmutable instance.")]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->name;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?\DateTimeInterface $expirationDate): static
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getUrlViewMode () {
        return $this->urlViewMode;
    }

    public function setUrlViewMode (string $urlViewMode) {
        $this->urlViewMode = $urlViewMode;
        return $this;
    }

    public function getUrlEditMode () {
        return $this->urlViewMode;
    }

    public function setUrlEditMode (string $urlEditMode) {
        $this->urlEditMode = $urlEditMode;
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
}
