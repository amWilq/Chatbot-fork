<?php

namespace App\Application\Dtos;

use App\Domain\Assessment\Types\CodeSnippetAssessment\CodeSnippetAssessment;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;
use \App\Domain\Assessment\Entities\AssessmentType;

#[Immutable]
readonly class CodeSnippetAssessmentDTO extends AssessmentTypeDTO
{
    private function __construct(
        private int $snippetCount,
        private int $correctSolutions,
        private array $codeSnippets,
        private AssessmentTypeDTO $assessmentTypeData
    ) {
        parent::__construct(
            id: $this->assessmentTypeData->getId(),
            name: $this->assessmentTypeData->getName(),
            description: $this->assessmentTypeData->getDescription(),
            difficulties: $this->assessmentTypeData->getDifficulties()
        );
    }

    protected function getSnippetCount(): int
    {
        return $this->snippetCount;
    }

    protected function getCorrectSolutions(): int
    {
        return $this->correctSolutions;
    }

    protected function getCodeSnippets(): array
    {
        return $this->codeSnippets;
    }

    public static function fromDomainEntity(
        CodeSnippetAssessment|AssessmentType $assessmentType
    ): self {
        $snippets = [];

        foreach ($assessmentType->getSnippetAttempts() as $question) {
            $snippets[] = CodeSnippetAttemptDTO::fromDomainEntity($question)
                ->toArray();
        }

        return new self(
            snippetCount: $assessmentType->getSnippetCount(),
            correctSolutions: $assessmentType->getCorrectSnippetCount(),
            codeSnippets: $snippets,
            assessmentTypeData: AssessmentTypeDTO::fromDomainEntity(
                $assessmentType->getAssessmentType()
            ),
        );
    }

    #[ArrayShape([
        'assessmentTypeId' => 'string',
        'assessmentTypeName' => 'string',
        'answeredQuestions' => 'int',
        'correctAnswers' => 'int',
        'duration' => 'int',
        'snippets' => 'array',
    ])]
    public function toArray(): array
    {
        return [
            'assessmentTypeId' => $this->getId(),
            'assessmentTypeName' => $this->getName(),
            'answeredQuestions' => $this->getSnippetCount(),
            'correctAnswers' => $this->getCorrectSolutions(),
            'snippets' => $this->getCodeSnippets(),
        ];
    }
}
