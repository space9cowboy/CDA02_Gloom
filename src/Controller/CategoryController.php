<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/api/categories', name: 'api_get_categories', methods: ['GET'])]
    public function getCategories(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();
        return $this->json($categories, 200, [], ['groups' => 'category:read', 'instrument:read']);
    }

    #[Route('/api/categories/{id}', name: 'api_get_category', methods: ['GET'])]
    public function getCategory(CategoryRepository $categoryRepository, int $id): JsonResponse
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            return new JsonResponse(['message' => 'Category not found'], 404);
        }

        return $this->json($category, 200, [], ['groups' => 'category:read', 'instrument:read']);
    }

    #[Route('/api/categories', name: 'api_create_category', methods: ['POST'])]
    public function createCategory(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data['name'] && !$data['description']) {
            return new JsonResponse(['message' => 'Category name/description is required'], 400);
        }

        $category = new Category();
        $category->setName($data['name']);
        $category->setDescription($data['description']);

        $entityManager->persist($category);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Category created successfully'], 201);
    }

    #[Route('/api/categories/{id}', name: 'api_update_category', methods: ['PUT'])]
    public function updateCategory(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            return new JsonResponse(['message' => 'Category not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $category->setName($data['name']);
        }
        if (isset($data['description'])) {
            $category->setDescription($data['description']);
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Category updated successfully']);
    }

    #[Route('/api/categories/{id}', name: 'api_delete_category', methods: ['DELETE'])]
    public function deleteCategory(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            return new JsonResponse(['message' => 'Category not found'], 404);
        }

        $entityManager->remove($category);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Category deleted successfully']);
    }
}
