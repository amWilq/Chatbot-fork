<?php

namespace App\Infrastructure\Controllers;

use App\Application\Services\LanguageService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LanguageController extends AbstractBaseController
{
    public function __construct(
        private LanguageService $languageService,
    ) {
    }

    #[Route('/languages', name: 'app.languages.all', methods: ['GET'])]
    public function getAllLanguages(): JsonResponse
    {
        $output = $this->languageService->getAllLanguages();
        return $this->prettyJsonResponse($output);
    }

    #[Route('//languages/category/{categoryId}', name: 'app.languages.by_category_id', methods: ['GET'])]
    public function getLanguagesByCategoryId(string $categoryId): JsonResponse
    {
        $output = $this->languageService->getLanguagesByCategoryId($categoryId);
        return $this->prettyJsonResponse($output);
    }
}
