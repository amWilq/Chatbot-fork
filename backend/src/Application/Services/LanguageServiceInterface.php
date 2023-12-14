<?php

namespace App\Application\Services;

interface LanguageServiceInterface
{
    public function getAllLanguages(): array;

    public function getLanguagesByCategoryId(string $categoryId): array;
}
