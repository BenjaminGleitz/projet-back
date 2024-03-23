<?php

namespace App\Controller\Category;

use App\Service\Category\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'app_category_', methods: ['GET', 'POST', 'PUT', 'DELETE'])]
class CategoryController extends AbstractController
{
    // Action pour récupérer toutes les catégories
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(CategoryService $categoryService): JsonResponse
    {
        $categories = $categoryService->getAllCategories();
        return $this->json($categories);
    }

    // Action pour récupérer une catégorie par son ID
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(CategoryService $categoryService, int $id): JsonResponse
    {
        $category = $categoryService->getCategory($id);
        return $this->json($category);
    }

    // Action pour créer une nouvelle catégorie
    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(Request $request, CategoryService $categoryService): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        try {
            $category = $categoryService->createCategory($requestData['name'], $requestData['image']);
            return $this->json($category, 201);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    // Action pour mettre à jour une catégorie existante
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, CategoryService $categoryService, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        try {
            $category = $categoryService->updateCategory($id, $requestData['name'], $requestData['image']);
            return $this->json($category);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    // Action pour supprimer une catégorie
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(CategoryService $categoryService, int $id): JsonResponse
    {
        $categoryService->deleteCategory($id);
        return $this->json([], 204);
    }
}
