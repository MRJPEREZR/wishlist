<?php

namespace App\DataFixtures;

use App\Entity\Purchase;
use App\Entity\User;
use App\Entity\WishList;
use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PurchaseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Fetch existing users, wishlists, and items from the database
        $users = $manager->getRepository(User::class)->findAll();
        $wishLists = $manager->getRepository(WishList::class)->findAll();
        $items = $manager->getRepository(Item::class)->findAll();

        if (empty($users) || empty($wishLists) || empty($items)) {
            throw new \Exception('Ensure users, wishlists, and items exist in the database before running this fixture.');
        }

        $purchasesData = [
            [
                'user' => $users[0],
                'wishList' => $wishLists[0],
                'item' => $items[0],
                'url_proof' => 'https://example.com/proof1.jpg',
                'message' => 'Hope you like this gift!',
            ],
            [
                'user' => $users[1],
                'wishList' => $wishLists[0],
                'item' => $items[1],
                'url_proof' => 'https://example.com/proof2.jpg',
                'message' => 'A little surprise for you!',
            ]
        ];

        foreach ($purchasesData as $data) {
            $purchase = new Purchase();
            $purchase->setUser($data['user']);
            $purchase->setWishList($data['wishList']);
            $purchase->setItem($data['item']);
            $purchase->setUrlProof($data['url_proof']);
            $purchase->setMessage($data['message']);
            $purchase->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($purchase);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            WishListFixtures::class,
            ItemFixtures::class,
        ];
    }
}
