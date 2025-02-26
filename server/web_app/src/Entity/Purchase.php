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

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?int $user = null;

    #[ORM\ManyToOne(targetEntity: WishList::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?int $wishList = null;

    #[ORM\ManyToOne(targetEntity: Item::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?int $item = null;

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

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(int $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getWishList(): ?int
    {
        return $this->wishlist;
    }

    public function setWishList(int $wishList): static
    {
        $this->wishList = $wishList;

        return $this;
    }

    public function getItem(): ?int
    {
        return $this->item;
    }

    public function setItem(int $item): static
    {
        $this->item = $item;

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
