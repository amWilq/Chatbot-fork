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
        private int $correctAnswerIndex,
        private string $explanation,
    ) {
    }

    public static function create(string $content, array $options, int $correctAnswerIndex, string $explanation): self
    {
        return new self(
            content: $content,
            options: $options,
            correctAnswerIndex: $correctAnswerIndex,
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

    public function getCorrectAnswer(): int
    {
        return $this->correctAnswerIndex;
    }

    public function getExplanation(): string
    {
        return $this->explanation;
    }

    public function isAnswerCorrect(int $userAnswer): bool
    {
        return $this->correctAnswerIndex === $userAnswer;
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
          && $this->correctAnswerIndex === $object->correctAnswerIndex
          && $this->explanation === $object->explanation
          && $this->arraysAreEqual($this->options, $object->options);
    }
}
