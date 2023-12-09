<?php

namespace App\Application\Dtos;

use App\Domain\Assessment\Entities\AssessmentType;
use App\Shared\Models\EntityToArrayInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
readonly class AssessmentTypeDTO implements EntityToArrayInterface
{

    protected function __construct(
      private string $id,
      private string $name,
      private string $description,
      private array $difficulties
    ) {}

    protected function getId(): string
    {
        return $this->id;
    }

    protected function getName(): string
    {
        return $this->name;
    }

    protected function getDescription(): string
    {
        return $this->description;
    }

    protected function getDifficulties(): array
    {
        return $this->difficulties;
    }

    public static function fromDomainEntity(AssessmentType $assessmentType
    ): self {
        return new self(
          id: $assessmentType->getId()->toString(),
          name: $assessmentType->getName(),
          description: $assessmentType->getDescription(),
          difficulties: $assessmentType->getDifficulties()
        );
    }

    #[ArrayShape([
      'assessmentTypeId' => 'string',
      'name' => 'string',
      'description' => 'string',
      'difficulties' => 'array',
    ])]
    public function toArray(): array
    {
        return [
          'assessmentTypeId' => $this->getId(),
          'name' => $this->getName(),
          'description' => $this->getDescription(),
          'difficulties' => $this->getDifficulties()
        ];
    }
}
