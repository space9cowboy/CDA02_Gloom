<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // POST: Envoie un message à un autre utilisateur concernant un instrument
    #[Route('/api/messages', name: 'api_send_message', methods: ['POST'])]
    public function sendMessage(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser(); // Récupérer l'utilisateur connecté (envoyeur)

        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], 401);
        }

        $message = new Message();
        $message->setSenderId($user->getId());
        $message->setReceiverId($data['receiver_id']);
        $message->setInstrumentId($data['instrument_id']);
        $message->setMessage($data['message']);

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Message sent successfully'], 201);
    }


    // GET: Récupère tous les messages échangés entre deux utilisateurs concernant un instrument spécifique
    #[Route('/api/messages/{instrumentId}/user/{userId}', name: 'api_get_conversation', methods: ['GET'])]
    public function getConversation(int $instrumentId, int $userId, MessageRepository $messageRepository): JsonResponse
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], 401);
        }

        $messages = $messageRepository->findBy([
            'instrument_id' => $instrumentId,
            'sender_id' => $userId,
            'receiver_id' => $user->getId(),
        ]);

        return $this->json($messages, 200);
    }

    // GET: Récupère tous les messages reçus par l'utilisateur connecté
    #[Route('/api/messages/received', name: 'api_get_received_messages', methods: ['GET'])]
    public function getReceivedMessages(MessageRepository $messageRepository): JsonResponse
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], 401);
        }

        $messages = $messageRepository->findBy(['receiver_id' => $user->getId()]);

        return $this->json($messages, 200);
    }

    // GET: Récupère tous les messages envoyés par l'utilisateur connecté
    #[Route('/api/messages/sent', name: 'api_get_sent_messages', methods: ['GET'])]
    public function getSentMessages(MessageRepository $messageRepository): JsonResponse
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], 401);
        }

        $messages = $messageRepository->findBy(['sender_id' => $user->getId()]);

        return $this->json($messages, 200);
    }
}
