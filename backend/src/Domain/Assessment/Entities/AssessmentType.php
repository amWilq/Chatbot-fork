<?php

namespace App\Domain\Assessment\Entities;

use App\Domain\Assessment\Enums\FormatEnum;
use App\Domain\Assessment\ValueObjects\AssessmentTypeId;
use App\Domain\Assessment\ValueObjects\Format;
use App\Shared\Models\AggregateRoot;
use App\Shared\Models\ValueObject;
use JetBrains\PhpStorm\ArrayShape;

class AssessmentType extends AggregateRoot
{

    protected ValueObject $id;

    protected Format $format;

    /**
     * AssessmentType constructor.
     */
    public function __construct(
        string $id,
        string $formatName,
    ) {
        $this->id = AssessmentTypeId::create($id) ?? AssessmentTypeId::create(
            AggregateRoot::generateId()
        );
        $this->format = Format::create(FormatEnum::tryFrom($formatName));
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
     * Get parent instance.
     */
    public function getParent(): self
    {
        return $this;
    }

}
