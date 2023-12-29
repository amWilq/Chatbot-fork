<?php

namespace App\Domain\Assessment\Types\CodeSnippetAssessment;

use App\Domain\Assessment\ValueObjects\CodeSnippet;

class CodeSnippetAttempt
{
    private ?string $userAnswer;

    private ?int $takenTime;

    private ?bool $isCorrect;

    private ?string $explanation;

    private function __construct(
        ?string $userAnswer,
        ?int $takenTime,
        ?bool $isCorrect,
        ?string $explanation,
        private readonly CodeSnippet $codeSnippet
    ) {
        $this->userAnswer = $userAnswer;
        $this->takenTime = $takenTime;
        $this->isCorrect = $isCorrect;
        $this->explanation = $explanation;
    }

    public static function create(
        CodeSnippet $codeSnippet,
        string $userAnswer = null,
        int $takenTime = null,
        bool $isCorrect = null,
        string $explanation = null,
    ): self {
        return new self(
            userAnswer: $userAnswer,
            takenTime: $takenTime,
            isCorrect: $isCorrect,
            explanation: $explanation,
            codeSnippet: $codeSnippet
        );
    }

    public function setAttempt(string $attempt, int $takenTime): void
    {
        $this->userAnswer = $attempt;
        $this->takenTime = $takenTime;
    }

    public function getAnswer(): ?string
    {
        return $this->userAnswer;
    }

    public function getTakenTime(): ?int
    {
        return $this->takenTime;
    }

    public function getCodeSnippet(): CodeSnippet
    {
        return $this->codeSnippet;
    }

    public function isCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function getExplanation(): ?string
    {
        return $this->explanation;
    }

    public function setExplanation(string $explanation): void
    {
        $this->explanation = $explanation;
    }

    public function setCorrectness(bool $correctness): void
    {
        $this->isCorrect = $correctness;
    }
}
