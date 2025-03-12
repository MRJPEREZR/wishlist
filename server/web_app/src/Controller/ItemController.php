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
    public function getAllItems(): JsonResponse
    {
        $items = $this->itemRepository->findAllItems();

        if (!$items) {
            return new JsonResponse(['error' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }

        $itemArray = array_map(fn($item) => 
        [
            'id' => $item->getId(),
            'wishList' => $item->getWishList(),
            'title' => $item->getTitle(),
            'description' =>$item->getDescription(),
            'price' =>$item->getPrice(),
            'purchaseUrl' =>$item->getPurchaseUrl(),
            'createdAt' => $item->getCreatedAt()    
        ]
        , $items);
        
        return $this->json([
            'items' => $itemArray,
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
