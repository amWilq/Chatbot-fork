<?php

namespace App\Domain\Assessment\ValueObjects;

use App\Shared\Models\ValueObject;

readonly class AssessmentId extends ValueObject
{
    /**
     * AssessmentId constructor.
     */
    private function __construct(
        private string $id
    ) {
    }

    /**
     * Get id as string.
     */
    public function toString(): string
    {
        return $this->id;
    }

    /**
     * @inheritDoc
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
