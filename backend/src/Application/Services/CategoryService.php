<?php

namespace App\Application\Services;

use App\Application\Dtos\CategoryDTO;
use App\Infrastructure\Persistence\Repository\CategoryEntityRepository;

class CategoryService
{
    public function __construct(
        private CategoryEntityRepository $categoryEntityRepository
    ) {}

    public function getAllCategories(): array
    {
        $categories = $this->categoryEntityRepository->findAll();
        return array_map(
            static fn ($cat) => CategoryDTO::fromDomainEntity($cat)->toArray(),
            $categories
        );
    }

    public function getCategoryById(string $id): ?array
    {
        $category = $this->categoryEntityRepository->find($id);
        return $category ? CategoryDTO::fromDomainEntity($category)->toArray() : null;
    }

}
