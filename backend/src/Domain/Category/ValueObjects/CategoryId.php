<?php

namespace App\Domain\Category\ValueObjects;

use App\Shared\Models\ValueObject;

class CategoryId extends ValueObject
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * Check if the current object is equal to the given object
     */
    public function equals(ValueObject $object): bool
    {
        if (!$object instanceof self) {
            return false;
        }
        return $this->id === $object->id;
    }

}
