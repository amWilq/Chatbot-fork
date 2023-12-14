<?php

namespace App\Application\Services;

interface CategoryServiceInterface
{
    public function getAllCategories(): array;

    public function getCategoryById(string $id): ?array;

}
