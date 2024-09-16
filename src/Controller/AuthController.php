<?php

// src/Controller/AuthController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Entity\User;

class AuthController extends AbstractController
{
    private $entityManager;
    private $passwordHasher;
    private $jwtManager;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->jwtManager = $jwtManager;
    }

    #[Route('/api/auth/signup', name: 'api_signup', methods: ['POST'])]
    public function signup(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data['email'] || !$data['password'] || !$data['username'] || !$data['type']) {
            return new JsonResponse(['message' => 'Missing required fields'], 400);
        }

        // Vérifier si l'utilisateur existe déjà
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse(['message' => 'Email already exists'], 400);
        }

        // Créer un nouvel utilisateur
        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
        $user->setType($data['type']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
        $user->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'User registered successfully'], 201);
    }

    #[Route('/api/auth/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifie si les champs requis sont présents
        if (empty($data['username']) || empty($data['password'])) {
            return new JsonResponse(['message' => 'Email and password are required'], 400);
        }

        // Recherche l'utilisateur par email
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $data['username']]);

        if (!$user) {
            return new JsonResponse(['message' => 'Invalid credentials'], 401);
        }

        // Vérifie si le mot de passe est correct
        if (!$this->passwordHasher->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['message' => 'Invalid credentials'], 401);
        }

        // Génère un token JWT
        $token = $this->jwtManager->create($user);

        return new JsonResponse(['userId' => $user->getId(), 'token' => $token], 200);
    }
}
