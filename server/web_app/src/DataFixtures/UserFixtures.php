<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $usersData = [
            [
                'user_name' => 'john_doe',
                'name' => 'John',
                'surname' => 'Doe',
                'email' => 'john.doe@example.com',
                'password' => 'password123',
                'is_blocked' => false,
                'role' => UserRole::ADMIN,
            ],
            [
                'user_name' => 'jane_smith',
                'name' => 'Jane',
                'surname' => 'Smith',
                'email' => 'jane.smith@example.com',
                'password' => 'securepass',
                'is_blocked' => false,
                'role' => UserRole::USER,
            ],
            [
                'user_name' => 'bob_martin',
                'name' => 'Bob',
                'surname' => 'Martin',
                'email' => 'bob.martin@example.com',
                'password' => 'test123',
                'is_blocked' => true,
                'role' => UserRole::USER,
            ]
        ];

        foreach ($usersData as $userData) {
            $user = new User();
            $user->setUserName($userData['user_name']);
            $user->setName($userData['name']);
            $user->setSurname($userData['surname']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $userData['password']));
            $user->setIsBlocked($userData['is_blocked']);
            $user->setRole($userData['role']);
            $user->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($user);
        }

        $manager->flush();
    }
}
