<?php

namespace App\Entity;

use App\Repository\WishListMemberRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WishListMemberRepository::class)]
class WishListMember
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: WishList::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "WishList must be provided.")]
    private ?WishList $wishList = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "User must be provided.")]
    private ?User $user = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Edit permission (canEdit) must be set.")]
    #[Assert\Type(type: 'bool', message: "canEdit must be a boolean value.")]
    private ?bool $canEdit = false;

    #[ORM\Column(nullable: true, options: ['default' => false])]
    #[Assert\Type(type: 'bool', message: "isAccepted must be a boolean value.")]
    private ?bool $isAccepted = false;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function isCanEdit(): ?bool
    {
        return $this->canEdit;
    }

    public function setCanEdit(bool $canEdit): static
    {
        $this->canEdit = $canEdit;
        return $this;
    }

    public function isAccepted(): ?bool
    {
        return $this->is_accepted;
    }

    public function setIsAccepted(?bool $isAccepted): static
    {
        $this->isAccepted = $isAccepted;
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
