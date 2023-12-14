<?php

namespace App\Domain\Assessment\ValueObjects;

use App\Domain\Assessment\Enums\FormatEnum;
use App\Shared\Models\ValueObject;

readonly class Format extends ValueObject
{
    /**
     * Format constructor.
     */
    private function __construct(
        private FormatEnum $name,
        private string $description,
        private array $difficulties,
    ) {
    }

    /**
     * Get name as string.
     */
    public function getName(): string
    {
        return $this->name->value;
    }

    /**
     * Get difficulties as array.
     */
    public function getDifficulties(): array
    {
        return $this->difficulties;
    }

    /**
     * Description property getter.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public function equals(ValueObject $object): bool
    {
        if (!$object instanceof self) {
            return false;
        }

        return $this->name === $object->name;
    }

    /**
     * Create a new instance of the current object.
     */
    public static function create(FormatEnum $name): self
    {
        return new self(
            name: $name,
            description: $name->getDescription(),
            difficulties: $name->getDifficulties(),
        );
    }
}
