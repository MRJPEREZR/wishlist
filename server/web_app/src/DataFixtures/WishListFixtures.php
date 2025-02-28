<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Item;
use App\Entity\WishList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class WishListFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Retrieve all users
        $users = $manager->getRepository(User::class)->findAll();
        // Retrieve all items
        $items = $manager->getRepository(Item::class)->findAll();

        if (empty($users) || empty($items)) {
            throw new \Exception('No users or items found. Please load UserFixtures and ItemFixtures first.');
        }

        $wishListsData = [
            [
                'name' => 'John\'s Birthday Wishlist',
                'description' => 'Birthday Wishlist',
                'user' => $users[0],  // Assuming at least one user exists
                'items' => [$items[0], $items[1]],  // Adding first two items
                'expiration_date' => new \DateTime('+30 days'),
                'is_active' => true,
                'url_view_mode' => 'http:://url_view_mode.com',
                'url_edit_mode' => 'http:://url_edit_mode.com',
            ],
            [
                'name' => 'Tech Gadgets Wishlist',
                'description' => 'Gadgets Wishlist',
                'user' => $users[1],  // Use second user if available, else first user
                'items' => [$items[2], $items[3], $items[4]],  // Adding three different items
                'expiration_date' => new \DateTime('+60 days'),
                'is_active' => true,
                'url_view_mode' => 'http:://url_view_mode.com',
                'url_edit_mode' => 'http:://url_edit_mode.com',
            ],
            [
                'name' => 'Home Essentials Wishlist',
                'description' => 'Essentials Wishlist',
                'user' => $users[2],  // Use third user if available, else first user
                'items' => [$items[1], $items[4]],  // Adding two different items
                'expiration_date' => new \DateTime('+90 days'),
                'is_active' => false,
                'url_view_mode' => 'http:://url_view_mode.com',
                'url_edit_mode' => 'http:://url_edit_mode.com',
            ],
        ];

        foreach ($wishListsData as $wishListData) {
            $wishList = new WishList();
            $wishList->setName($wishListData['name']);
            $wishList->setDescription($wishListData['description']);
            $wishList->setUser($wishListData['user']);
            $wishList->setExpirationDate($wishListData['expiration_date']);
            $wishList->setIsActive($wishListData['is_active']);
            $wishList->setUrlViewMode($wishListData['url_view_mode']);
            $wishList->setUrlEditMode($wishListData['url_edit_mode']);
            $wishList->setCreatedAt(new \DateTimeImmutable());

            foreach ($wishListData['items'] as $item) {
                $wishList->getItems()->add($item);
            }

            $manager->persist($wishList);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ItemFixtures::class,
        ];
    }
}
