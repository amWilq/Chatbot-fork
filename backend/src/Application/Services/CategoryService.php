<?php

namespace App\Application\Services;

use App\Application\Dtos\CategoryDTO;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryEntityRepository
    ) {
    }

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
