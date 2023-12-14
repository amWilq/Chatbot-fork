<?php

namespace App\Infrastructure\Controllers;

use App\Application\Services\LanguageServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/languages', name: 'app.languages.')]
class LanguageController extends AbstractBaseController
{
    public function __construct(
        private readonly LanguageServiceInterface $languageService,
    ) {
    }

    #[Route('', name: 'all', methods: ['GET'])]
    public function getAllLanguages(): JsonResponse
    {
        $output = $this->languageService->getAllLanguages();
        return $this->prettyJsonResponse($output);
    }

    #[Route('/category/{categoryId}', name: 'by_category_id', methods: ['GET'])]
    public function getLanguagesByCategoryId(string $categoryId): JsonResponse
    {
        $output = $this->languageService->getLanguagesByCategoryId($categoryId);
        return $this->prettyJsonResponse($output);
    }
}
