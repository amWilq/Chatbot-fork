<?php

namespace App\Infrastructure\Controllers;

use App\Application\Services\CategoryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractBaseController
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {
    }

    #[Route('/categories', name: 'app.categories.all', methods: ['GET'])]
    public function getAllCategories(): JsonResponse
    {
        $output = $this->categoryService->getAllCategories();

        return $this->prettyJsonResponse($output);
    }

    #[Route('/categories/{categoryId}', name: 'app.categories.single', methods: ['GET'])]
    public function getCategoryById(string $categoryId): JsonResponse
    {
        $output = $this->categoryService->getCategoryById($categoryId);

        if (!$output) {
            return new JsonResponse("Category with id: $id was not found.", 400);
        }

        return $this->prettyJsonResponse($output);
    }
}
