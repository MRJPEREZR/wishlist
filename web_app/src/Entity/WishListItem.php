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
    #[ORM\ManyToOne(targetEntity: WishList::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?int $wishList = null;

    #[ORM\Column]
    #[ORM\ManyToOne(targetEntity: Item::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?int $item = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWishList(): ?int
    {
        return $this->wishList;
    }

    public function setWishList(int $wishList): static
    {
        $this->wishList = $wishList;

        return $this;
    }

    public function getItem(): ?int
    {
        return $this->item_id;
    }

    public function setItem(int $item): static
    {
        $this->item = $item;

        return $this;
    }
}
