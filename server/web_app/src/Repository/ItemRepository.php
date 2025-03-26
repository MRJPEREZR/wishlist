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

    public function findAllItemsByWishListFiltered(int $wishListId, ?bool $isBought, string $sortBy, string $sort, int $max, int $page)
    {
        $queryBuilder = $this->createQueryBuilder('i')
            ->andWhere('i.wishList = :wishListId')
            ->setParameter('wishListId', $wishListId);

        // Filter by bought status (optional)
        if ($isBought !== null) {
            if ($isBought) {
                $queryBuilder->innerJoin('App\Entity\Purchase', 'p', 'WITH', 'p.item = i.id'); // Only bought items
            } else {
                $queryBuilder->leftJoin('App\Entity\Purchase', 'p', 'WITH', 'p.item = i.id')
                            ->andWhere('p.item IS NULL'); // Only non-bought items
            }
        }

        // Ensure sorting by either 'createdAt' or 'price'
        if (!in_array($sortBy, ['createdAt', 'price'])) {
            $sortBy = 'createdAt'; // Default sorting by date
        }

        // Ensure sort order is either 'asc' or 'desc'
        if (!in_array(strtolower($sort), ['asc', 'desc'])) {
            $sort = 'desc'; // Default descending
        }

        $queryBuilder->orderBy("i.$sortBy", $sort)
                    ->setMaxResults($max)
                    ->setFirstResult(($page - 1) * $max);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findTotalPriceOfBoughtItemsByWishList(int $wishListId, string $sort = 'asc'): array
    {
        $queryBuilder = $this->createQueryBuilder('i')
            ->select('i.wishList AS wishListId, SUM(i.price) AS totalPrice')
            ->innerJoin('App\Entity\Purchase', 'p', 'WITH', 'p.item = i.id')
            ->where('i.wishList = :wishListId')
            ->setParameter('wishListId', $wishListId)
            ->groupBy('i.wishList');

        // Ensure sort order is either 'asc' or 'desc'
        if (!in_array(strtolower($sort), ['asc', 'desc'])) {
            $sort = 'asc'; // Default ascending order
        }

        $queryBuilder->orderBy('totalPrice', $sort);

        return $queryBuilder->getQuery()->getSingleResult() ?? ['wishListId' => $wishListId, 'totalPrice' => 0];
    }

    public function findWishListsSortedByTotalBought(string $sort = 'desc', int $max = 10, int $page = 1): array
    {
        $queryBuilder = $this->createQueryBuilder('i')
            ->select('w.id AS wishListId, SUM(i.price) AS totalPrice')
            ->innerJoin('i.wishList', 'w') // Join WishList entity
            ->innerJoin('App\Entity\Purchase', 'p', 'WITH', 'p.item = i.id') // Join Purchases
            ->groupBy('w.id');

        // Ensure sort order is either 'asc' or 'desc'
        if (!in_array(strtolower($sort), ['asc', 'desc'])) {
            $sort = 'desc'; // Default descending order
        }

        $queryBuilder->orderBy('totalPrice', $sort)
                    ->setMaxResults($max)
                    ->setFirstResult(($page - 1) * $max);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findByWishListSorted($wishList, string $sortOrder = 'asc')
    {
        $queryBuilder = $this->createQueryBuilder('i')
            ->andWhere('i.wishList = :wishList')
            ->setParameter('wishList', $wishList);
    
        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }
    
        $queryBuilder->orderBy('i.price', $sortOrder);
    
        return $queryBuilder->getQuery()->getResult();
    }    
}
