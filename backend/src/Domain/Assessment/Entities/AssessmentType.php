<?php

namespace App\Domain\Assessment\Entities;

use App\Domain\Assessment\Enums\FormatEnum;
use App\Domain\Assessment\ValueObjects\AssessmentTypeId;
use App\Domain\Assessment\ValueObjects\Format;
use App\Shared\Models\AggregateRoot;
use App\Shared\Models\ValueObject;

class AssessmentType extends AggregateRoot
{
    /**
     * AssessmentType constructor.
     */
    private function __construct(
        protected ValueObject $id,
        protected Format $format
    ) {
    }

    /**
     * Create a new instance of the current object
     */
    public static function create(string $formatName): self
    {
        return new self(
            id: AssessmentTypeId::create(AggregateRoot::generateId()),
            format: Format::create(FormatEnum::tryFrom($formatName))
        );
    }

    /**
     * Get format name.
     */
    public function getName(): string
    {
        return $this->format->getName();
    }

    /**
     * Get format description.
     */
    public function getDescription(): string
    {
        return $this->format->getDescription();
    }

    /**
     * Get format difficulties.
     */
    public function getDifficulties(): array
    {
        return $this->format->getDifficulties();
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'difficulties' => $this->getDifficulties(),
        ];
    }
}
