<?php

namespace App\Domain\Language\ValueObjects;

use App\Shared\Models\EntityToStringInterface;
use App\Shared\Models\ValueObject;

readonly class LanguageId extends ValueObject implements EntityToStringInterface
{
    /**
     * LanguageId constructor.
     */
    private function __construct(
        private string $id
    ) {
    }

    /**
     * Create a new instance of the current object
     */
    public static function create(string $languageId): self
    {
        return new self(
            id: $languageId
        );
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
}
