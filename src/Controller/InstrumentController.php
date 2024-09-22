<?php

namespace App\Controller;

use App\Entity\Instrument;
use App\Entity\Review;
use App\Repository\InstrumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InstrumentController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/instruments', name: 'api_get_instruments', methods: ['GET'])]
    public function getInstruments(InstrumentRepository $instrumentRepository): JsonResponse
    {
        $instruments = $instrumentRepository->findAll();

        return $this->json($instruments, 200, [], ['groups' => 'user:read','instrument:read']);
    }

    #[Route('/api/instruments/{id}', name: 'api_get_instrument', methods: ['GET'])]
    public function getInstrument(InstrumentRepository $instrumentRepository, int $id): JsonResponse
    {
        $instrument = $instrumentRepository->find($id);

        if (!$instrument) {
            return new JsonResponse(['message' => 'Instrument not found'], 404);
        }

        return $this->json($instrument, 200, [], ['groups' => 'user:read','instrument:read']);
    }

    #[Route('/api/instruments', name: 'api_create_instrument', methods: ['POST'])]
    public function createInstrument(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], 401);
        }

        $instrument = new Instrument();
        $instrument->setTitle($data['title']);
        $instrument->setDescription($data['description']);
        $instrument->setPrice($data['price']);
        $instrument->setImage($data['image']);
        $instrument->setCategory($data['category']);
        $instrument->setStatus($data['status'] ?? 'En vente');
        $instrument->setBrand($data['brand']);
        $instrument->setModel($data['model']);
        $instrument->setLocation($data['location']);
        $instrument->setSeller($user); // Associe l'utilisateur connecté comme vendeur
        $instrument->setCreatedAt(new \DateTimeImmutable());
        $instrument->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($instrument);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Instrument created successfully'], 201);
    }

    #[Route('/api/instruments/{id}', name: 'api_update_instrument', methods: ['PUT'])]
    public function updateInstrument(Request $request, InstrumentRepository $instrumentRepository, int $id): JsonResponse
    {
        $instrument = $instrumentRepository->find($id);

        if (!$instrument) {
            return new JsonResponse(['message' => 'Instrument not found'], 404);
        }

        $user = $this->getUser();

        // Vérifie que c'est bien le propriétaire qui modifie l'instrument
        if ($instrument->getSeller() !== $user) {
            return new JsonResponse(['message' => 'Unauthorized'], 403);
        }

        $data = json_decode($request->getContent(), true);

        $instrument->setTitle($data['title']);
        $instrument->setDescription($data['description']);
        $instrument->setPrice($data['price']);
        $instrument->setCategory($data['category']);
        $instrument->setBrand($data['brand']);
        $instrument->setModel($data['model']);
        $instrument->setLocation($data['location']);
        $instrument->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Instrument updated successfully']);
    }

    #[Route('/api/instruments/{id}', name: 'api_delete_instrument', methods: ['DELETE'])]
    public function deleteInstrument(InstrumentRepository $instrumentRepository, int $id): JsonResponse
    {
        $instrument = $instrumentRepository->find($id);

        if (!$instrument) {
            return new JsonResponse(['message' => 'Instrument not found'], 404);
        }

        $user = $this->getUser();

        // Vérifie que c'est bien le propriétaire qui supprime l'instrument
        if ($instrument->getSeller() !== $user) {
            return new JsonResponse(['message' => 'Unauthorized'], 403);
        }

        $this->entityManager->remove($instrument);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Instrument deleted successfully']);
    }

    // #[Route('/api/instruments/{id}/review', name: 'api_add_review', methods: ['POST'])]
    // public function addReview(Request $request, Instrument $instrument, EntityManagerInterface $entityManager): JsonResponse
    // {
    //     $data = json_decode($request->getContent(), true);
    //     $user = $this->getUser();

    //     if (!$user) {
    //         return new JsonResponse(['message' => 'User not authenticated'], 401);
    //     }

    //     $review = new Review();
    //     $review->setComment($data['comment']);
    //     $review->setRating($data['rating']);
    //     $review->setUser($user);
    //     $review->setInstrument($instrument);

    //     // Ajout de l'avis à l'instrument
    //     $instrument->addReview($review);

    //     // Mise à jour de la note moyenne de l'instrument
    //     $instrument->setRating($this->calculateAverageRating($instrument));

    //     $entityManager->persist($review);
    //     $entityManager->flush();

    //     return new JsonResponse(['message' => 'Review added successfully'], 201);
    // }

    // private function calculateAverageRating(Instrument $instrument): float
    // {
    //     $reviews = $instrument->getReviews();
    //     $totalRating = 0;
    //     $count = count($reviews);

    //     foreach ($reviews as $review) {
    //         $totalRating += $review->getRating();
    //     }

    //     return $count > 0 ? $totalRating / $count : 0;
    // }

    // #[Route('/api/instruments/bestrated', name: 'api_best_rated_instruments', methods: ['GET'])]
    // public function getBestRatedInstruments(InstrumentRepository $instrumentRepository): JsonResponse
    // {
    //     $bestRatedInstruments = $instrumentRepository->findBy([], ['rating' => 'DESC'], 10);

    //     return $this->json($bestRatedInstruments, 200, [], ['groups' => 'instrument:read']);
    // }
}
