<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\WishListRepository;
use App\Repository\ItemRepository;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/wishLists')]
final class WishListController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private WishListRepository $wishListRepository;
    private ItemRepository $itemRepository;
    private PurchaseRepository $purchaseRepository;
    private ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        WishListRepository $wishListRepository,
        ItemRepository $itemRepository,
        PurchaseRepository $purchaseRepository,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->wishListRepository = $wishListRepository;
        $this->itemRepository = $itemRepository;
        $this->purchaseRepository = $purchaseRepository;
        $this->validator = $validator;
    }

    #[Route('/{wishListId}', name: 'wish_list_show')]
    public function showWishList($wishListId, Request $request): Response
    {
        $sortOrder = $request->query->get('sortOrder', 'asc');
        $items = $this->getItemsForWishList($wishListId, $sortOrder);

        $wishListData = [
            'id' => $wishListId,
            'title' => 'Wish List ' . $wishListId,
        ];

        return $this->render('shared_wishlist.html.twig', [
            'wishList' => $wishListData,
            'items' => $items,
        ]);
    }

    #[Route('/{wishListId}/items', methods: ['GET'])]
    public function getWishListItems(Request $request): JsonResponse
    {
        $wishListId = $request->get('wishListId');
        $wishList = $this->wishListRepository->find($wishListId);
        if (!$wishList) {
            return new JsonResponse(['error' => 'WishList not found'], Response::HTTP_NOT_FOUND);
        }
        $sortOrder = $request->query->get('sortOrder', 'asc');
        
        // 使用私有方法获取完整数据（包含 purchaseMessage 和 purchaseDate）
        $items = $this->getItemsForWishList($wishListId, $sortOrder);
    
        if (empty($items)) {
            return new JsonResponse(['error' => 'No items found for this wishList'], Response::HTTP_NOT_FOUND);
        }
    
        return $this->json([
            'items' => $items
        ]);
    }
    
/**
 * 获取指定愿望清单中的所有物品，并整合是否已购买标记和礼物留言信息
 *
 * @param mixed  $wishListId
 * @param string $sortOrder
 *
 * @return array
 */
private function getItemsForWishList($wishListId, string $sortOrder): array
{
    // 检查愿望清单是否存在
    $wishList = $this->wishListRepository->find($wishListId);
    if (!$wishList) {
        return [];
    }

    // 获取物品列表，排序逻辑在 repository 中实现（确保按照 price 排序）
    $items = $this->itemRepository->findByWishListSorted($wishListId, $sortOrder);
    if (!$items) {
        return [];
    }

    // 获取所有物品ID，并查找对应的购买记录
    $itemIds = array_map(fn($item) => $item->getId(), $items);
    $purchases = $this->purchaseRepository->findBy(['item' => $itemIds]);

    // 构建一个 itemId 到 purchase 的映射，假设每个物品最多只有一条购买记录
    $purchaseMap = [];
    foreach ($purchases as $purchase) {
        $purchaseMap[$purchase->getItem()->getId()] = $purchase;
    }

    // 组装最终数组数据，并增加礼物留言和购买日期字段
    $itemArray = array_map(function($item) use ($purchaseMap) {
        $isBought = isset($purchaseMap[$item->getId()]);
        $purchaseMessage = $isBought ? $purchaseMap[$item->getId()]->getMessage() : '';
        $purchaseDate = $isBought ? $purchaseMap[$item->getId()]->getCreatedAt()->format('Y-m-d H:i:s') : '';
        
        return [
            'id' => $item->getId(),
            'title' => $item->getTitle(),
            'description' => $item->getDescription(),
            'price' => $item->getPrice(),
            'purchaseUrl' => $item->getPurchaseUrl(),
            'createdAt' => $item->getCreatedAt(),
            'is_bought' => $isBought,
            'purchaseMessage' => $purchaseMessage,
            'purchaseDate' => $purchaseDate,
        ];
    }, $items);

    return $itemArray;
}

}
