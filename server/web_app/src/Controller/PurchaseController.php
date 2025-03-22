<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PurchaseController extends AbstractController
{
    private PurchaseRepository $purchaseRepository;
    private EntityManagerInterface $entityManager;

    #[Route('/purchases/{itemId}/initiate', name: "purchase_initiate", methods: ['GET'])]
    public function initiatePurchase($itemId): Response
    {
        $item= $this->itemRepository->find($itemId);
        if (!$item) {
            return new Response('Item not found', Response::HTTP_NOT_FOUND);
        }

        $purchase = new Purchase();
        $purchase->setItem($item);
        $purchase->setUser($this->getUser());
        $purchase->setCreatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($purchase);
        $this->entityManager->flush();

        $purchaseUrl = $item->getPurchaseUrl();
        $proofUrl = $this->generateUrl('purchase_upload_proof', ['purchaseId' => $purchase->getId()]);
        
    }

    #[Route('/purchases/{purchaseId}/upload', name: "purchase_upload_proof", methods: ['GET','POST'])]
    public function uploadProof(Request $request, $purchaseId): Response
    {
        $purchase = $this->purchaseRepository->find($purchaseId);
        if (!$purchase) {
            return new Response('Purchase not found', Response::HTTP_NOT_FOUND);
        }

        $form = $this->createFormBuilder()
            ->add('proofFile', FileType::class, ['label' => 'Proof of purchase', 'mapped' => false, 'required' => true])
            ->add('congratulatoryText', TextareaType::class, ['label' => 'Congratulatory text'])
            ->add('submit', SubmitType::class, ['label' => 'Upload proof'])
            ->getForm();

        $form->handleRequest($request);
            $uploadedFile = $form['proofFile']->getData();
            if($uploadedFile) {
                $uploadsDir = $this->getParameter('uploads_directory');
                $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move($uploadsDir, $newFilename);
                $purchase->getUrlProof($newFilename);
            }
    }
}
