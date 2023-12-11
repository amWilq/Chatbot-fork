<?php

namespace App\Application\Services;

use App\Application\Dtos\LanguageDTO;
use App\Infrastructure\Persistence\Entities\CategoryEntity;
use App\Infrastructure\Persistence\Repository\CategoryEntityRepository;
use App\Infrastructure\Persistence\Repository\LanguageEntityRepository;

class LanguageService
{
    public function __construct(
        private readonly LanguageEntityRepository $languageEntityRepository,
        private readonly CategoryEntityRepository $categoryEntityRepository,
    ) {
    }

    /**
     * @return LanguageDTO[]
     */
    public function getAllLanguages(): array
    {
        $languages = $this->languageEntityRepository->findAll();

        return array_map(
            static fn ($lang) => LanguageDTO::fromDomainEntity($lang)->toArray(),
            $languages
        );
    }

    /**
     * @return LanguageDTO[]
     */
    public function getLanguagesByCategoryId(string $categoryId): array
    {
        $languages = $this->languageEntityRepository->findByCategoryId($categoryId);

        return array_map(
            static fn ($lang) => LanguageDTO::fromDomainEntity($lang)->toArray(),
            $languages
        );
    }
}
