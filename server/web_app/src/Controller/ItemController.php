<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/items')]
final class ItemController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ItemRepository $itemRepository;
    private UserPasswordHasherInterface $passwordHasher;
    private ValidatorInterface $validator;
    public function __construct(
        EntityManagerInterface $entityManager,
        ItemRepository $itemRepository,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->itemRepository = $itemRepository;
        $this->passwordHasher = $passwordHasher;
        $this->validator = $validator;
    }

    #[Route('', methods: ['GET'])]
    public function getAllItems(Request $req): JsonResponse
    {
        // Validate "onlyBought" as boolean (default: false)
        $onlyBought = filter_var($req->query->get('onlyBought', false), 
            FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;

        // Validate "sortBy" to allow only 'createdAt' or 'price'
        $allowedSortBy = ['createdAt', 'price'];
        $sortBy = $req->query->get('sortBy', 'createdAt');
        if (!in_array($sortBy, $allowedSortBy, true)) {
            $sortBy = 'price';
        }

        // Validate "sort" to allow only 'asc' or 'desc'
        $allowedSort = ['asc', 'desc'];
        $sort = strtolower($req->query->get('sort', 'asc'));
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'desc';
        }

        // Validate "max" as a positive integer (default: 10)
        $max = filter_var($req->query->get('max', 10), FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]) ?: 10;

        // Validate "page" as a positive integer (default: 1)
        $page = filter_var($req->query->get('page', 1), FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]) ?: 1;

        // Fetch items from repository with filters
        $items = $this->itemRepository->findAllItemsFiltered($onlyBought, $sortBy,
             $sort, $max, $page);

        if (!$items) {
            return new JsonResponse(['error' => 'No items found'], Response::HTTP_NOT_FOUND);
        }

        $itemArray = array_map(fn($item) => [
            'id' => $item->getId(),
            'wishList' => $item->getWishList(),
            'title' => $item->getTitle(),
            'description' => $item->getDescription(),
            'price' => $item->getPrice(),
            'purchaseUrl' => $item->getPurchaseUrl(),
            'createdAt' => $item->getCreatedAt()
        ], $items);

        return $this->json([
            'items' => $itemArray,
            'page' => $page,
            'max' => $max,
            'sortBy' => $sortBy,
            'sort' => $sort,
            'onlyBought' => $onlyBought,
            'path' => 'src/Controller/ItemController.php',
        ]);

    }

    #[Route('/{itemId}', methods: ['GET'])]
    public function getItemsById($itemId): JsonResponse
    {
        $item = $this->itemRepository->findOneById($itemId);

        if (!$item) {
            return new JsonResponse(['error' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }

        $itemDTO = [
            'id' => $item->getId(),
            'wishList' => $item->getWishList(),
            'title' => $item->getTitle(),
            'description' =>$item->getDescription(),
            'price' =>$item->getPrice(),
            'purchaseUrl' =>$item->getPurchaseUrl(),
            'createdAt' => $item->getCreatedAt()    
        ];

        return $this->json([
            'user' => $itemDTO,
            'path' => 'src/Controller/ItemController.php',
        ]);
    }
}
