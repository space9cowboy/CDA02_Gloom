<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\Instrument;
use App\Entity\User;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // POST: Créer une nouvelle transaction
    #[Route('/api/transactions', name: 'api_create_transaction', methods: ['POST'])]
    public function createTransaction(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser(); // Récupérer l'utilisateur connecté (acheteur)

        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], 401);
        }

        // Récupérer l'instrument à partir de son ID
        $instrument = $this->entityManager->getRepository(Instrument::class)->find($data['instrument_id']);
        if (!$instrument) {
            return new JsonResponse(['message' => 'Instrument not found'], 404);
        }

        // Récupérer le vendeur (propriétaire de l'instrument)
        $seller = $instrument->getSeller();
        if (!$seller) {
            return new JsonResponse(['message' => 'Seller not found'], 404);
        }

        if ($user->getId() === $seller->getId()) {
            return new JsonResponse(['message' => 'Buyer and seller cannot be the same user'], 400);
        }

        // Créer la transaction
        $transaction = new Transaction();
        $transaction->setBuyerId($user->getId());
        $transaction->setSellerId($seller->getId());
        $transaction->setInstrumentId($instrument->getId());
        $transaction->setTransactionAmount($data['transaction_amount']);

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Transaction created successfully'], 201);
    }

    // GET: Récupérer toutes les transactions de l'utilisateur connecté
    #[Route('/api/transactions', name: 'api_get_transactions', methods: ['GET'])]
    public function getTransactions(TransactionRepository $transactionRepository): JsonResponse
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], 401);
        }

        // Récupérer les transactions de l'utilisateur connecté (acheteur)
        $transactions = $transactionRepository->findBy(['buyer_id' => $user->getId()]);

        return $this->json($transactions, 200);
    }

    // GET: Récupérer les détails d'une transaction spécifique
    #[Route('/api/transactions/{id}', name: 'api_get_transaction', methods: ['GET'])]
    public function getTransaction(TransactionRepository $transactionRepository, int $id): JsonResponse
    {
        $transaction = $transactionRepository->find($id);

        if (!$transaction) {
            return new JsonResponse(['message' => 'Transaction not found'], 404);
        }

        return $this->json($transaction, 200);
    }

        // GET: Récupérer les transactions par Buyer ID
        #[Route('/api/transactions/buyer/{buyerId}', name: 'api_get_transactions_by_buyer', methods: ['GET'])]
        public function getTransactionByBuyerId(int $buyerId, TransactionRepository $transactionRepository): JsonResponse
        {
            $transactions = $transactionRepository->findBy(['buyer_id' => $buyerId]);
    
            if (!$transactions) {
                return new JsonResponse(['message' => 'No transactions found for this buyer'], 404);
            }
    
            return $this->json($transactions, 200, [], ['groups' => 'transaction:read']);
        }
    
        // GET: Récupérer les transactions par Seller ID
        #[Route('/api/transactions/seller/{sellerId}', name: 'api_get_transactions_by_seller', methods: ['GET'])]
        public function getTransactionBySellerId(int $sellerId, TransactionRepository $transactionRepository): JsonResponse
        {
            $transactions = $transactionRepository->findBy(['seller_id' => $sellerId]);
    
            if (!$transactions) {
                return new JsonResponse(['message' => 'No transactions found for this seller'], 404);
            }
    
            return $this->json($transactions, 200, [], ['groups' => 'transaction:read']);
        }
    
}
