<?php

namespace App\Domain\User\ValueObjects;

use App\Shared\Models\ValueObject;

readonly class UserDeviceId extends ValueObject
{

    /**
     * UserDeviceId constructor.
     */
    private function __construct(
        protected string $id,
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
     * Create a new instance of the current object
     */
    public static function create(string $id): self
    {
        return new self(
            id: $id
        );
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
