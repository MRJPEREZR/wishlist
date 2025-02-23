<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column]
    private ?int $wishlist_id = null;

    #[ORM\Column]
    private ?int $item_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url_proof = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $message = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getWishlistId(): ?int
    {
        return $this->wishlist_id;
    }

    public function setWishlistId(int $wishlist_id): static
    {
        $this->wishlist_id = $wishlist_id;

        return $this;
    }

    public function getItemId(): ?int
    {
        return $this->item_id;
    }

    public function setItemId(int $item_id): static
    {
        $this->item_id = $item_id;

        return $this;
    }

    public function getUrlProof(): ?string
    {
        return $this->url_proof;
    }

    public function setUrlProof(?string $url_proof): static
    {
        $this->url_proof = $url_proof;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
