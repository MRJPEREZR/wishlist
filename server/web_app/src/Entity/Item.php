<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: WishList::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    #[Assert\NotNull(message: "WishList must be selected.")]
    private ?WishList $wishList = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Title cannot be empty.")]
    #[Assert\Length(max: 255, maxMessage: "Title cannot be longer than 255 characters.")]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Description cannot be longer than 255 characters.")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Price cannot be null.")]
    #[Assert\Positive(message: "Price must be a positive number.")]
    private ?float $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $purchaseUrl = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "CreatedAt must be set.")]
    #[Assert\Type(type: \DateTimeImmutable::class, message: "CreatedAt must be a valid DateTimeImmutable instance.")]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWishList(): ?WishList
    {
        return $this->wishList;
    }

    public function setWishList(WishList $wishList): static
    {
        $this->wishList = $wishList;
        return $this;
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

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getPurchaseUrl(): ?string
    {
        return $this->purchaseUrl;
    }

    public function setPurchaseUrl(?string $purchaseUrl): static
    {
        $this->purchaseUrl = $purchaseUrl;
        return $this;
    }

    public function isBought(): ?bool
    {
        return $this->isBought;
    }

    public function setIsBought(?bool $isBought): static
    {
        $this->isBought = $isBought;
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
