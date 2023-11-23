<?php

namespace App\Domain\Assessment\ValueObjects;

use App\Shared\Models\ValueObject;

class AssessmentId extends ValueObject
{
    /**
     * AssessmentId constructor.
     */
    private function __construct(
        private readonly string $id
    ) {
    }

    /**
     * Get id as string.
     */
    public function getId(): string
    {
        return $this->id;
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

    /**
     * Create a new instance of the current object
     */
    public static function create($id): self
    {
        return new self(
            id: $id
        );
    }
}
