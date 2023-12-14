<?php

namespace App\Domain\Category\Repositories;

use App\Domain\Category\Entities\Category;
use App\Infrastructure\Persistence\Entities\CategoryEntity;
use App\Port\Outbound\OutboundPortInterface;

interface CategoryRepositoryInterface extends OutboundPortInterface
{
    public function find($id, $lockMode = null, $lockVersion = null, bool $raw = false): null|Category|CategoryEntity;

    public function findOneBy(array $criteria, array $orderBy = null, bool $raw = false): null|Category|CategoryEntity;

    /**
     * @return Category[]|CategoryEntity[]
     */
    public function findAll(bool $raw = false): array;

    /**
     * @return Category[]|CategoryEntity[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null, bool $raw = false): array;

    public function save(Category $aggregateRoot): void;

    public function delete(Category $aggregateRoot): void;

}
