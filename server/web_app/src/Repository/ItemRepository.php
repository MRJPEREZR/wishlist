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

    // Custom query method to find an item by id
    public function findOneById(int $id): ?Item
    {
        return $this->findOneBy(['id' => $id]);
    }

    // Custom method to manage pagination and filters.
    public function findAllItemsFiltered(?int $wishList, bool $onlyBought, string $sortBy, string $sort, int $max, int $page)
    {
        $queryBuilder = $this->createQueryBuilder('i');

        // Filter by wishListId if provided
        if ($wishList !== null) {
            $queryBuilder->andWhere('i.wishList = :wishListId')
                        ->setParameter('wishListId', $wishList);
        }

        if ($onlyBought) {
            $queryBuilder->innerJoin('App\Entity\Purchase', 'p', 'WITH', 'p.item = i.id');
        }

        // This ensures sortBy is either 'createdAt' or 'price'
        if (!in_array($sortBy, ['createdAt', 'price'])) {
            $sortBy = 'price'; // Default to sorting by price
        }

        // Ensure sort order is either 'asc' or 'desc'
        if (!in_array(strtolower($sort), ['asc', 'desc'])) {
            $sort = 'desc'; // Default to sorting by desc
        }

        $queryBuilder->orderBy("i.$sortBy", $sort)
                    ->setMaxResults($max)
                    ->setFirstResult(($page - 1) * $max);

        return $queryBuilder->getQuery()->getResult();
    }



}
