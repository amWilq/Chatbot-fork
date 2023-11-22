<?php

namespace App\Shared\Models;

interface RepositoryInterface
{
    /**
     * Saves an aggregate root.
     */
    public function save(AggregateRoot $aggregateRoot): void;

    /**
     * Removes an aggregate root.
     */
    public function delete(AggregateRoot $aggregateRoot): void;

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
