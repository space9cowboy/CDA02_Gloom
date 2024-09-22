<?php
// src/Controller/UserController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Review;
use App\Entity\Instrument;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/user', name: 'api_get_users', methods: ['GET'])]
    public function getUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        return $this->json($users, 200, [], ['groups' => 'user:read']);
    }

    #[Route('/api/user/{id}', name: 'api_get_user', methods: ['GET'])]
    public function getOneUser(UserRepository $userRepository, int $id): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        return $this->json($user, 200, [], ['groups' => 'user:read']);
    }


    #[Route('/api/user/{id}/review', name: 'api_add_review', methods: ['POST'])]
    public function addReview(Request $request, User $userNoted, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], 401);
        }

        $review = new Review();
        $review->setComment($data['comment']);
        $review->setRating($data['rating']);
        $review->setUser($user);
        $review->setUserNoted($userNoted);

        // Ajout de l'avis à l'user
        $userNoted->addReviewReceive($review);
        // $user->addReviewSend($review);

        // Mise à jour de la note moyenne de l'user
        $userNoted->setRating($this->calculateAverageRating($userNoted));

        $entityManager->persist($review);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Review added successfully'], 201);
    }

    private function calculateAverageRating(User $userNoted): float
    {
        $reviews = $userNoted->getReviewsReceive();
        $totalRating = 0;
        $count = count($reviews);

        foreach ($reviews as $review) {
            $totalRating += $review->getRating();
        }

        return $count > 0 ? $totalRating / $count : 0;
    }

    #[Route('/api/user/bestrated', name: 'api_best_rated_user', methods: ['GET'])]
    public function getBestRatedUser(UserRepository $userRepository): JsonResponse
    {
        $bestRatedUser = $userRepository->findBy([], ['rating' => 'DESC'], 10);

        return $this->json($bestRatedUser, 200, [], ['groups' => 'user:read']);
    }

    #[Route('/api/user/favorites/{instrumentId}', name: 'api_add_favorite', methods: ['POST'])]
    public function addFavorite(int $instrumentId, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer l'utilisateur connecté
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], 401);
        }

        // Récupérer l'instrument à partir de l'id
        $instrument = $entityManager->getRepository(Instrument::class)->find($instrumentId);
        if (!$instrument) {
            return new JsonResponse(['message' => 'Instrument not found'], 404);
        }

        // Ajouter l'instrument à la liste des favoris de l'utilisateur
        $user->addFavori($instrument);

        // Sauvegarder dans la base de données
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Instrument added to favorites'], 200);
    }
   


}
