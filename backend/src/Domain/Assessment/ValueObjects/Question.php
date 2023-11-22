<?php

namespace App\Domain\Assessment\ValueObjects;

use App\Shared\Models\ValueObject;

class Question extends ValueObject
{
    protected string $content;
    /** @var string[] $options */
    protected array $options;
    protected string $correctAnswer;
    protected string $explanation;
    protected string $userAnswer;
    protected bool $isCorrect;
    protected string $takenTime;
}
