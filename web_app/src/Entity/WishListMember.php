<?php

namespace App\Entity;

use App\Repository\WishListMemberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WishListMemberRepository::class)]
class WishListMember
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[ORM\ManyToOne(targetEntity: Wishlist::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?int $wishlist_id = null;

    #[ORM\Column]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?int $user_id = null;

    #[ORM\Column]
    private ?bool $can_edit = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_accepted = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function isCanEdit(): ?bool
    {
        return $this->can_edit;
    }

    public function setCanEdit(bool $can_edit): static
    {
        $this->can_edit = $can_edit;

        return $this;
    }

    public function isAccepted(): ?bool
    {
        return $this->is_accepted;
    }

    public function setIsAccepted(?bool $is_accepted): static
    {
        $this->is_accepted = $is_accepted;

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

    public function setId(?Wishlist $id): static
    {
        $this->id = $id;

        return $this;
    }
}
