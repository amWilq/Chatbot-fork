<?php

namespace App\Domain\Assessment\Entities;

use App\Domain\Assessment\Enums\DifficultiesEnum;

class QuizAssessment extends AssessmentType
{
    protected int $questionCount;
    protected int $correctAnswerCount;
    protected DifficultiesEnum $startDifficulty;
    protected DifficultiesEnum $endDifficulty;
    protected string $duration;

    /** @var Question[] $questions  */
    protected array $questions;

}
