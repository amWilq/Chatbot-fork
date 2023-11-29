<?php

namespace App\Domain\Assessment\Types\QuizAssessment;

use App\Domain\Assessment\ValueObjects\Question;

class QuestionAttempt
{
    private int $userAnswer;
    private int $takenTime;
    private bool $isCorrect;

    private function __construct(
        private readonly Question $question
    ) {
    }

    public static function create(Question $question): self
    {
        return new self(
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

    public function getTakenTime(): int
    {
        return $this->takenTime;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function checkAnswerCorrectness(): bool
    {
        $this->isCorrect = $this->question->isAnswerCorrect($this->userAnswer);
        return $this->isCorrect;
    }
}
