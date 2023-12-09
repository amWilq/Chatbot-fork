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
}
