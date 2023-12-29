<?php

namespace App\Application\Dtos;

use App\Domain\Assessment\Types\CodeSnippetAssessment\CodeSnippetAttempt;
use App\Domain\Assessment\Types\QuizAssessment\QuestionAttempt;
use App\Shared\Models\EntityToArrayInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
readonly class CodeSnippetAttemptDTO implements EntityToArrayInterface
{

    private function __construct(
        private string $code,
        private string $correctSolution,
        private ?string $explanation,
        private string $userAnswer,
        private bool $isCorrect,
        private int $takenTime
    ) {
    }

    protected function getCode(): string
    {
        return $this->code;
    }

    protected function getCorrectSolution(): string
    {
        return $this->correctSolution;
    }

    protected function getExplanation(): ?string
    {
        return $this->explanation;
    }

    protected function getUserAnswer(): string
    {
        return $this->userAnswer;
    }

    protected function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    protected function getTakenTime(): int
    {
        return $this->takenTime;
    }

    public static function fromDomainEntity(CodeSnippetAttempt $codeSnippetAttempt
    ): self {
        return new self(
            code: $codeSnippetAttempt->getCodeSnippet()->getCode(),
            correctSolution: $codeSnippetAttempt->getCodeSnippet()->getCorrectSolution(),
            explanation: $codeSnippetAttempt->getExplanation(),
            userAnswer: $codeSnippetAttempt->getAnswer(),
            isCorrect: $codeSnippetAttempt->isCorrect(),
            takenTime: $codeSnippetAttempt->getTakenTime(),
        );
    }

    #[ArrayShape([
        'code' => 'string',
        'correctSolution' => 'string',
        'explanation' => 'string',
        'userAnswer' => 'string',
        'isCorrect' => 'bool',
        'takenTime' => 'int',
    ])]
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'correctSolution' => $this->getCorrectSolution(),
            'explanation' => $this->getExplanation(),
            'userAnswer' => $this->getUserAnswer(),
            'isCorrect' => $this->isCorrect(),
            'takenTime' => $this->getTakenTime(),
        ];
    }

}
