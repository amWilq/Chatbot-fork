<?php

namespace App\Infrastructure\Controllers;

use App\Application\Services\CategoryServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/categories', name: 'app.categories.')]
class CategoryController extends AbstractBaseController
{
    public function __construct(
        private readonly CategoryServiceInterface $categoryService
    ) {
    }

    #[Route('', name: 'all', methods: ['GET'])]
    public function getAllCategories(): JsonResponse
    {
        $output = $this->categoryService->getAllCategories();

        return $this->prettyJsonResponse($output);
    }

    #[Route('/{categoryId}', name: 'single', methods: ['GET'])]
    public function getCategoryById(string $categoryId): JsonResponse
    {
        $output = $this->categoryService->getCategoryById($categoryId);

        if (!$output) {
            return new JsonResponse("Category with id: $categoryId was not found.", 400);
        }

        return $this->prettyJsonResponse($output);
    }
}
