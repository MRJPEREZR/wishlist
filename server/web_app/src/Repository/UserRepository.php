<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Enum\UserRole;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllUsers (): array {
        return $this->findAll();
    }

    // Custom query method to find active users
    public function findActiveUsers(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.isBlocked = :isBlocked')
            ->setParameter('isBlocked', false)
            ->getQuery()
            ->getResult();
    }

    // Custom query method to find a user by id
    public function findOneById(int $id): ?User
    {
        return $this->findOneBy(['id' => $id]);
    }

    // Custom query method to find a user by email
    public function findOneByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    // Custom query method to find users by role
    public function findByRole(UserRole $role): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.role = :role')
            ->setParameter('role', $role)
            ->getQuery()
            ->getResult();
    }

    public function findAllUsersFiltered(?UserRole $userRole, ?bool $isBlocked, string $sortBy, string $sort, int $max, int $page)
    {
        $queryBuilder = $this->createQueryBuilder('u');

        // Filter by userRole if provided
        if ($userRole) {
            $queryBuilder->andWhere('u.role LIKE :role')
                         ->setParameter('role', $userRole->value);
        }

        // Filter by isBlocked if provided
        if (!is_null($isBlocked)) {
            $queryBuilder->andWhere('u.isBlocked = :isBlocked')
                        ->setParameter('isBlocked', $isBlocked);
        }

        // Apply sorting
        $queryBuilder->orderBy("u.$sortBy", $sort)
                    ->setMaxResults($max)
                    ->setFirstResult(($page - 1) * $max);

        return $queryBuilder->getQuery()->getResult();
    }
}
