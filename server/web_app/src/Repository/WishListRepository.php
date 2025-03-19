<?php

namespace App\Repository;

use App\Entity\WishList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WishList>
 */
class WishListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WishList::class);
    }

    public function findAllItems (): array {
        return $this->findAll();
    }

    public function findAllWishListsFiltered(?int $userId, ?\DateTime $expirationDate, ?bool $isActive, string $sortBy, string $sort, int $max, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('w');

        // Filter by user ID if provided
        if ($userId !== null) {
            $queryBuilder->andWhere('w.user = :userId')
                        ->setParameter('userId', $userId);
        }

        // Filter by expirationDate if provided
        if ($expirationDate !== null) {
            $queryBuilder->andWhere('w.expirationDate >= :expirationDate')
                        ->setParameter('expirationDate', $expirationDate);
        }

        // Filter by active status if provided
        if ($isActive !== null) {
            $queryBuilder->andWhere('w.isActive = :isActive')
                        ->setParameter('isActive', $isActive);
        }

        // Ensure sorting by either 'createdAt' or 'expirationDate'
        if (!in_array($sortBy, ['createdAt', 'expirationDate'])) {
            $sortBy = 'createdAt'; // Default sorting by date created
        }

        // Ensure sort order is either 'asc' or 'desc'
        if (!in_array(strtolower($sort), ['asc', 'desc'])) {
            $sort = 'desc'; // Default descending
        }

        $queryBuilder->orderBy("w.$sortBy", $sort)
                    ->setMaxResults($max)
                    ->setFirstResult(($page - 1) * $max);

        return $queryBuilder->getQuery()->getResult();
    }
}
