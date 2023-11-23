<?php

namespace App\Domain\Assessment\Entities;

use App\Domain\Assessment\Enums\DifficultiesEnum;
use App\Domain\Assessment\ValueObjects\Question;

final class QuizAssessment
{
    private function __construct(
        protected int $questionCount,
        protected int $correctAnswerCount,
        protected DifficultiesEnum $startDifficulty,
        protected DifficultiesEnum $endDifficulty,
        protected string $duration,
        protected array $questions
    ) {

    }
}
