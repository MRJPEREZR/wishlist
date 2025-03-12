<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findAllItems (): array {
        return $this->findAll();
    }

    // Custom query method to find a user by id
    public function findOneById(int $id): ?Item
    {
        return $this->findOneBy(['id' => $id]);
    }
}
