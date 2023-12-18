<?php

namespace App\Application\Dtos;

use App\Domain\Assessment\Types\QuizAssessment\QuestionAttempt;
use App\Shared\Models\EntityToArrayInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
readonly class QuestionAttemptDTO implements EntityToArrayInterface
{

    private function __construct(
        private string $content,
        private array $answers,
        private string $correctAnswer,
        private ?string $explanation,
        private string $yourAnswer,
        private bool $isCorrect,
        private int $takenTime
    ) {
    }

    protected function getContent(): string
    {
        return $this->content;
    }

    protected function getAnswers(): array
    {
        return $this->answers;
    }

    protected function getCorrectAnswer(): string
    {
        return $this->correctAnswer;
    }

    protected function getExplanation(): ?string
    {
        return $this->explanation;
    }

    protected function getYourAnswer(): string
    {
        return $this->yourAnswer;
    }

    protected function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    protected function getTakenTime(): int
    {
        return $this->takenTime;
    }

    public static function fromDomainEntity(QuestionAttempt $questionAttempt
    ): self {
        return new self(
            content: $questionAttempt->getQuestion()->getContent(),
            answers: $questionAttempt->getQuestion()->getOptions(),
            correctAnswer: $questionAttempt->getQuestion()->getCorrectAnswer(),
            explanation: $questionAttempt->getExplanation(),
            yourAnswer: $questionAttempt->getAnswer(),
            isCorrect: $questionAttempt->isCorrect(),
            takenTime: $questionAttempt->getTakenTime(),
        );
    }

    #[ArrayShape([
        'content' => 'string',
        'answers' => 'array',
        'correctAnswer' => 'string',
        'explanation' => 'string',
        'yourAnswer' => 'string',
        'isCorrect' => 'bool',
        'takenTime' => 'int',
    ])]
    public function toArray(): array
    {
        return [
            'content' => $this->getContent(),
            'answers' => $this->getAnswers(),
            'correctAnswer' => $this->getCorrectAnswer(),
            'explanation' => $this->getExplanation(),
            'yourAnswer' => $this->getYourAnswer(),
            'isCorrect' => $this->isCorrect(),
            'takenTime' => $this->getTakenTime(),
        ];
    }

}
