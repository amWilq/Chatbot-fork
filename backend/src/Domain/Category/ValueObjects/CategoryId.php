<?php

namespace App\Domain\Category\ValueObjects;

use App\Shared\Models\ValueObject;

readonly class CategoryId extends ValueObject
{
    /**
     * CategoryId constructor.
     */
    private function __construct(
        private string $id,
    ) {
    }

    /**
     * Create a new instance of the current object
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
    public function getId(): string
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

}
