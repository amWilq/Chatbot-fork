<?php

namespace App\Domain\User\ValueObjects;

use App\Shared\Models\EntityToStringInterface;
use App\Shared\Models\ValueObject;

readonly class UserIdentity extends ValueObject implements EntityToStringInterface
{
    /**
     * UserId constructor.
     */
    private function __construct(
        protected string $id,
    ) {
    }
    /**
     * Create a new instance of the current object
     */
    public static function create(string $id): self
    {
        return new static(
            id: $id
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
