<?php

namespace App\Shared\Models;

abstract class AggregateRoot
{

    protected ValueObject $id;

    /**
     * AggregateRoot constructor.
     */
    public function __construct(ValueObject $id)
    {
        $this->id = $id;
    }

    /**
     * Returns the ID of the aggregate root.
     */
    public function getId(): ValueObject
    {
        return $this->id;
    }

    /**
     * Check if the current object is equal to the given object
     */
    public function equals(AggregateRoot $other): bool
    {
        return $this->getId()->equals($other->getId());
    }
}
