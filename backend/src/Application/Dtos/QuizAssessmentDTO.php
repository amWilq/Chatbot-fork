<?php

namespace App\Application\Dtos;

use App\Domain\Assessment\Entities\AssessmentType;
use App\Domain\Assessment\Types\QuizAssessment\QuizAssessment;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
readonly class QuizAssessmentDTO extends AssessmentTypeDTO
{

    private function __construct(
        private int $answeredQuestions,
        private int $correctAnswers,
        private int $duration,
        private array $questions,
        private AssessmentTypeDTO $assessmentTypeData
    ) {
        parent::__construct(
            id: $this->assessmentTypeData->getId(),
            name: $this->assessmentTypeData->getName(),
            description: $this->assessmentTypeData->getDescription(),
            difficulties: $this->assessmentTypeData->getDifficulties()
        );
    }

    protected function getAnsweredQuestions(): int
    {
        return $this->answeredQuestions;
    }

    protected function getCorrectAnswers(): int
    {
        return $this->correctAnswers;
    }

    protected function getDuration(): int
    {
        return $this->duration;
    }

    protected function getQuestions(): array
    {
        return $this->questions;
    }

    public static function fromDomainEntity(
        QuizAssessment|AssessmentType $assessmentType
    ): self {
        $questions = [];

        foreach ($assessmentType->getQuestionsAttempts() as $question) {
            $questions[] = QuestionAttemptDTO::fromDomainEntity($question)
                ->toArray();
        }

        return new self(
            answeredQuestions: $assessmentType->getQuestionCount(),
            correctAnswers: $assessmentType->getCorrectAnswerCount(),
            duration: $assessmentType->getDuration(),
            questions: $questions,
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
        'questions' => 'array',
    ])]
    public function toArray(): array
    {
        return [
            'assessmentTypeId' => $this->getId(),
            'assessmentTypeName' => $this->getName(),
            'answeredQuestions' => $this->getAnsweredQuestions(),
            'correctAnswers' => $this->getCorrectAnswers(),
            'duration' => $this->getDuration(),
            'questions' => $this->getQuestions(),
        ];
    }

}
