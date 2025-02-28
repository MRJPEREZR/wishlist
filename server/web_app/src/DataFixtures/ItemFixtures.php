<?php

namespace App\DataFixtures;

use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ItemFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $itemsData = [
            [
                'title' => 'Smartphone',
                'description' => 'Latest model with high-end specs',
                'price' => 799.99,
                'is_bought' => true,
                'purchase_url' => 'https://example.com/smartphone',
            ],
            [
                'title' => 'Laptop',
                'description' => 'Powerful laptop for work and gaming',
                'price' => 1299.49,
                'is_bought' => true,
                'purchase_url' => 'https://example.com/laptop',
            ],
            [
                'title' => 'Wireless Headphones',
                'description' => 'Noise-canceling headphones with long battery life',
                'price' => 199.99,
                'is_bought' => false,
                'purchase_url' => 'https://example.com/headphones',
            ],
            [
                'title' => 'Coffee Maker',
                'description' => 'Automatic coffee maker with multiple settings',
                'price' => 89.99,
                'is_bought' => false,
                'purchase_url' => 'https://example.com/coffee-maker',
            ],
            [
                'title' => 'Gaming Chair',
                'description' => 'Ergonomic chair for long gaming sessions',
                'price' => 249.99,
                'is_bought' => false,
                'purchase_url' => 'https://example.com/gaming-chair',
            ]
        ];

        foreach ($itemsData as $itemData) {
            $item = new Item();
            $item->setTitle($itemData['title']);
            $item->setDescription($itemData['description']);
            $item->setPrice($itemData['price']);
            $item->setIsBought($itemData['is_bought']);
            $item->setPurchaseUrl($itemData['purchase_url']);
            $item->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($item);
        }

        $manager->flush();
    }
}
