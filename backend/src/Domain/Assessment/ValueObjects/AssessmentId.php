<?php

namespace App\Domain\Assessment\ValueObjects;

use App\Shared\Models\ValueObject;

class AssessmentId extends ValueObject
{
    private string $id;
    public function getId(): string
    {
        return $this->id;
    }

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
