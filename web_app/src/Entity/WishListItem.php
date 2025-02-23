<?php

namespace App\Entity;

use App\Repository\WishListItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WishListItemRepository::class)]
class WishListItem
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
    #[ORM\ManyToOne(targetEntity: Item::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?int $item_id = null;

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

    public function getItemId(): ?int
    {
        return $this->item_id;
    }

    public function setItemId(int $item_id): static
    {
        $this->item_id = $item_id;

        return $this;
    }
}
