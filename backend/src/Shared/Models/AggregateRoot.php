<?php

namespace App\Shared\Models;

abstract class AggregateRoot
{
    protected ValueObject $id;

    /**
     * Returns the ID of the aggregate root.
     */
    public function getId(): ValueObject
    {
        return $this->id;
    }

    /**
     * Check if the current object is equal to the given object.
     */
    public function equals(AggregateRoot $other): bool
    {
        return $this->getId()->equals($other->getId());
    }

    /**
     * Generate random 8 characters unique ID.
     */
    public static function generateId(): string
    {
        return bin2hex(random_bytes(4));
    }
}
