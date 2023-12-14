<?php

namespace App\Domain\Assessment\Types\QuizAssessment;

use App\Domain\Assessment\ValueObjects\Question;

class QuestionAttempt
{
    private string $userAnswer;

    private int $takenTime;

    private bool $isCorrect;

    private string $explanation;

    private function __construct(
        ?string $userAnswer,
        ?int $takenTime,
        ?bool $isCorrect,
        ?string $explanation,
        private readonly Question $question
    ) {
        $this->userAnswer = $userAnswer;
        $this->takenTime = $takenTime;
        $this->isCorrect = $isCorrect;
        $this->explanation = $explanation;
    }

    public static function create(
        Question $question,
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
            question: $question
        );
    }

    public function setAnswer(int $answerIndex, int $takenTime): void
    {
        $this->userAnswer = $answerIndex;
        $this->takenTime = $takenTime;
    }

    public function getAnswer(): int
    {
        return $this->userAnswer;
    }

    public function setUserAnswer(string $userAnswer): void
    {
        $this->userAnswer = $userAnswer;
    }

    public function getTakenTime(): int
    {
        return $this->takenTime;
    }

    public function setTakenTime(int $takenTime): void
    {
        $this->takenTime = $takenTime;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function getExplanation(): string
    {
        return $this->explanation;
    }

    public function setExplanation(string $explanation): void
    {
        $this->explanation = $explanation;
    }

    public function checkAnswerCorrectness(): bool
    {
        $this->isCorrect = $this->question->isAnswerCorrect($this->userAnswer);

        return $this->isCorrect;
    }
}
