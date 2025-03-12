<?php

namespace App\DataFixtures;

use App\Entity\Item;
use App\Entity\WishList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ItemFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $wishLists = $manager->getRepository(WishList::class)->findAll();

        if (empty($wishLists)) {
            throw new \Exception('Ensure wishlists exists in the database before running this fixture.');
        }

        $itemsData = [
            [   
                'wishList' => $wishLists[0],
                'title' => 'Smartphone',
                'description' => 'Latest model with high-end specs',
                'price' => 799.99,
                'purchase_url' => 'https://example.com/smartphone',
            ],
            [
                'wishList' => $wishLists[1],
                'title' => 'Laptop',
                'description' => 'Powerful laptop for work and gaming',
                'price' => 1299.49,
                'purchase_url' => 'https://example.com/laptop',
            ],
            [
                'wishList' => $wishLists[2],
                'title' => 'Wireless Headphones',
                'description' => 'Noise-canceling headphones with long battery life',
                'price' => 199.99,
                'purchase_url' => 'https://example.com/headphones',
            ],
            [
                'wishList' => $wishLists[0],
                'title' => 'Coffee Maker',
                'description' => 'Automatic coffee maker with multiple settings',
                'price' => 89.99,
                'purchase_url' => 'https://example.com/coffee-maker',
            ],
            [
                'wishList' => $wishLists[1],
                'title' => 'Gaming Chair',
                'description' => 'Ergonomic chair for long gaming sessions',
                'price' => 249.99,
                'purchase_url' => 'https://example.com/gaming-chair',
            ]
        ];

        foreach ($itemsData as $itemData) {
            $item = new Item();
            $item->setWishList($itemData['wishList']);
            $item->setTitle($itemData['title']);
            $item->setDescription($itemData['description']);
            $item->setPrice($itemData['price']);
            $item->setPurchaseUrl($itemData['purchase_url']);
            $item->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($item);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            WishListFixtures::class
        ];
    }
}
