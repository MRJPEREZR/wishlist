<?php

namespace App\DataFixtures;

use App\Entity\WishListMember;
use App\Entity\User;
use App\Entity\WishList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class WishListMemberFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Fetch existing users and wishlists from the database
        $users = $manager->getRepository(User::class)->findAll();
        $wishLists = $manager->getRepository(WishList::class)->findAll();

        if (empty($users) || empty($wishLists)) {
            throw new \Exception('Make sure you have users and wishlists in the database before running this fixture.');
        }

        $wishListMembersData = [
            [
                'wishList' => $wishLists[0], 
                'user' => $users[0], 
                'can_edit' => true, 
                'is_accepted' => true,
            ],
            [
                'wishList' => $wishLists[0], 
                'user' => $users[1], 
                'can_edit' => false, 
                'is_accepted' => true,
            ],
            [
                'wishList' => $wishLists[1], 
                'user' => $users[2], 
                'can_edit' => true, 
                'is_accepted' => false,
            ]
        ];

        foreach ($wishListMembersData as $data) {
            $wishListMember = new WishListMember();
            $wishListMember->setWishList($data['wishList']);
            $wishListMember->setUser($data['user']);
            $wishListMember->setCanEdit($data['can_edit']);
            $wishListMember->setIsAccepted($data['is_accepted']);
            $wishListMember->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($wishListMember);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            WishListFixtures::class,
        ];
    }
}