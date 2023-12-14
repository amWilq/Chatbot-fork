<?php

namespace App\Domain\Category\ValueObjects;

use App\Shared\Models\EntityToStringInterface;
use App\Shared\Models\ValueObject;

readonly class CategoryId extends ValueObject implements EntityToStringInterface
{
    /**
     * CategoryId constructor.
     */
    private function __construct(
        private string $id,
    ) {
    }

    /**
     * Create a new instance of the current object.
     */
    public static function create(string $categoryId): self
    {
        return new self(
            id: $categoryId,
        );
    }

    /**
     * Get id as string.
     */
    public function toString(): string
    {
        return $this->id;
    }

    public function equals(ValueObject $object): bool
    {
        if (!$object instanceof self) {
            return false;
        }

        return $this->id === $object->id;
    }
}
