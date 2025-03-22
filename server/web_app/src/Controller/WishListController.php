<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\WishListRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/wishLists')]
final class WishListController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private WishListRepository $wishListRepository;
    private ItemRepository $itemRepository;
    private ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        WishListRepository $wishListRepository,
        ItemRepository $itemRepository,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->wishListRepository = $wishListRepository;
        $this->itemRepository = $itemRepository;
        $this->validator = $validator;
    }

    #[Route('', methods: ['GET'])]
    public function getAllWishLists(Request $request): JsonResponse
    {
        // Get query parameters with defaults
        $userId = $request->query->get('userId');
        $userId = $userId !== null ? (int) $userId : null;

        $expirationDate = $request->query->get('expirationDate');
        $expirationDate = $expirationDate !== null ? new \DateTime($expirationDate) : null;

        $isActive = $request->query->get('isActive');
        $isActive = $isActive !== null ? filter_var($isActive, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null;

        $sortBy = $request->query->get('sortBy', 'createdAt');
        $sort = $request->query->get('sort', 'desc');

        $max = (int) $request->query->get('max', 10); // Default 10 items per page
        $page = (int) $request->query->get('page', 1); // Default page 1

        $wishLists = $this->wishListRepository->findAllWishListsFiltered($userId, $expirationDate, $isActive, $sortBy, $sort, $max, $page);

        if (!$wishLists) {
            return new JsonResponse(['error' => 'No wishLists found'], Response::HTTP_NOT_FOUND);
        }

        $wishListArray = array_map(fn($wishList) => [
            'id' => $wishList->getId(),
            'user' => $wishList->getUser()->getId(),
            'name' => $wishList->getName(),
            'description' => $wishList->getDescription(),
            'expirationDate' => $wishList->getExpirationDate(),
            'isActive' => $wishList->isActive(),
            'createdAt' => $wishList->getCreatedAt(),
        ], $wishLists);

        return $this->json([
            'wishLists' => $wishListArray,
            'pagination' => [
                'max' => $max,
                'page' => $page
            ],
            'path' => 'src/Controller/WishListController.php',
        ]);
    }


    // #[Route('/{wishListId}/items', methods: ['GET'])]
    // public function getWishListItems(int $wishListId, Request $req): JsonResponse
    // {
    //     // Get query parameters with defaults
    //     $isBought = $req->query->get('isBought');
    //     $isBought = $isBought !== null ? filter_var($isBought, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null;

    //     $sortBy = $req->query->get('sortBy', 'createdAt');
    //     $sort = $req->query->get('sort', 'desc');

    //     $max = (int) $req->query->get('max', 10); // Default 10 items per page
    //     $page = (int) $req->query->get('page', 1); // Default page 1

    //     $items = $this->itemRepository->findAllItemsByWishListFiltered($wishListId, $isBought, $sortBy, $sort, $max, $page);

    //     if (!$items) {
    //         return new JsonResponse(['error' => 'No items found for this wishList'], Response::HTTP_NOT_FOUND);
    //     }

    //     $itemArray = array_map(fn($item) => [
    //         'id' => $item->getId(),
    //         'title' => $item->getTitle(),
    //         'description' => $item->getDescription(),
    //         'price' => $item->getPrice(),
    //         'purchaseUrl' => $item->getPurchaseUrl(),
    //         'createdAt' => $item->getCreatedAt(),
    //     ], $items);

    //     return $this->json([
    //         'items' => $itemArray,
    //         'pagination' => [
    //             'max' => $max,
    //             'page' => $page
    //         ],
    //         'path' => 'src/Controller/WishListController.php',
    //     ]);
    // }

    // #[Route('/sortedByTotalBought', methods: ['GET'])]
    // public function getWishListsSortedByTotalBought(Request $request): JsonResponse
    // {
    //     $sort = $request->query->get('sort', 'desc'); // Default sorting to descending
    //     $max = (int) $request->query->get('max', 10); // Default 10 per page
    //     $page = (int) $request->query->get('page', 1); // Default page 1

    //     $wishLists = $this->itemRepository->findWishListsSortedByTotalBought($sort, $max, $page);

    //     return $this->json([
    //         'wishLists' => $wishLists,
    //         'pagination' => [
    //             'max' => $max,
    //             'page' => $page
    //         ],
    //         'path' => 'src/Controller/WishListController.php',
    //     ]);
    // }

    /*
    * @author: Xinlei
    * @update: 2025-03-21
    * @description: view all items in a wish list
    */
    
    #[Route('/{wishListId}/items', methods: ['GET'])]
    public function getWishListItems(Request $request): JsonResponse
    {
        $wishListId = $request->get('wishListId');
        $wishList = $this->wishListRepository->find($wishListId);
        if(!$wishList) {
            return new JsonResponse(['error' => 'WishList not found'], Response::HTTP_NOT_FOUND);
        }
        $sortOrder = $request->query->get('sortOrder', 'asc');
        $items = $this->$itemRepository->findByWishListSorted($wishListId, $sortOrder);

        if (!$items) {
            return new JsonResponse(['error' => 'No items found for this wishList'], Response::HTTP_NOT_FOUND);
        }

        $itemIds = array_map(fn($item) => $item->getId(), $items);
        $purchases = $this->$purchaseRepository->findBy(['item' => $itemIds]);
        $purchasedItemIds =[];
        foreach($purchases as $purchase) {
            $purchasedItemIds[] = $purchase->getItem()->getId();
        }

        $itemArray = array_map(fn($item) => [
            'id' => $item->getId(),
            'title' => $item->getTitle(),
            'description' => $item->getDescription(),
            'price' => $item->getPrice(),
            'purchaseUrl' => $item->getPurchaseUrl(),
            'createdAt' => $item->getCreatedAt(),
            'is_bought'    => in_array($item->getId(), $purchasedItemIds),
        ], $items);

        return $this->json([
            'items' => $itemArray
        ]);
    }
}
