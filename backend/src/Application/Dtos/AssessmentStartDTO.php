<?php

namespace App\Application\Dtos;

use App\Shared\Models\EntityToArrayInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
readonly class AssessmentStartDTO implements EntityToArrayInterface
{
    private function __construct(
        private string $assessmentState,
        private string $assessmentId,
        private string $assessmentTypeId,
        private string $categoryId,
        private string $languageId,
        private string $difficulty,
        private string $startTime,
    ) {
    }

    protected function getAssessmentState(): string
    {
        return $this->assessmentState;
    }

    protected function getAssessmentId(): string
    {
        return $this->assessmentId;
    }

    protected function getAssessmentTypeId(): string
    {
        return $this->assessmentTypeId;
    }

    protected function getCategoryId(): string
    {
        return $this->categoryId;
    }

    protected function getLanguageId(): string
    {
        return $this->languageId;
    }

    protected function getDifficulty(): string
    {
        return $this->difficulty;
    }

    protected function getStartTime(): string
    {
        return $this->startTime;
    }

    public static function create(
        string $assessmentState,
        string $assessmentId,
        string $assessmentTypeId,
        string $categoryId,
        string $languageId,
        string $difficulty,
        string $startTime,
    ): self {
        return new self(
            assessmentState: $assessmentState,
            assessmentId: $assessmentId,
            assessmentTypeId: $assessmentTypeId,
            categoryId: $categoryId,
            languageId: $languageId,
            difficulty: $difficulty,
            startTime: $startTime,
        );
    }

    #[ArrayShape([
        'assessmentState' => 'string',
        'assessmentId' => 'string',
        'assessmentTypeId' => 'string',
        'categoryId' => 'string',
        'languageId' => 'string',
        'difficulty' => 'string',
        'startTime' => 'string',
    ])]
    public function toArray(): array
    {
        return [
            'assessmentState' => $this->getAssessmentState(),
            'assessmentId' => $this->getAssessmentId(),
            'assessmentTypeId' => $this->getAssessmentTypeId(),
            'categoryId' => $this->getCategoryId(),
            'languageId' => $this->getLanguageId(),
            'difficulty' => $this->getDifficulty(),
            'startTime' => $this->getStartTime(),
        ];
    }
}
