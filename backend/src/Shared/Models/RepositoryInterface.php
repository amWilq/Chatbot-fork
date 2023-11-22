<?php

namespace App\Shared\Models;

interface RepositoryInterface
{

    /**
     * Finds an aggregate root by its ID.
     */
    public function findById(ValueObject $id): ?AggregateRoot;

    /**
     * Finds all aggregate roots with optional criteria.
     *
     * @return AggregateRoot[]
     */
    public function findAll(array $criteria = []): array;
}
