<?php

namespace App\Domain\Assessment\ValueObjects;

use App\Shared\Models\ValueObject;
use App\Shared\Traits\HelperTrait;

readonly class Question extends ValueObject
{
    use HelperTrait;

    private function __construct(
        private string $content,
        private array $options,
        private string $correctAnswer,
        private string $explanation,
    ) {
    }

    public static function create(string $content, array $options, string $correctAnswer, string $explanation): self
    {
        return new self(
            content: $content,
            options: $options,
            correctAnswer: $correctAnswer,
            explanation: $explanation
        );
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getCorrectAnswer(): string
    {
        return $this->correctAnswer;
    }

    public function getExplanation(): string
    {
        return $this->explanation;
    }

    public function isAnswerCorrect(string $userAnswer): bool
    {
        return $this->correctAnswer === $userAnswer;
    }

    /**
     * {@inheritDoc}
     */
    public function equals(ValueObject $object): bool
    {
        if (!$object instanceof self) {
            return false;
        }

        return $this->content === $object->content
            && $this->correctAnswer === $object->correctAnswer
            && $this->explanation === $object->explanation
            && $this->arraysAreEqual($this->options, $object->options);
    }
}
