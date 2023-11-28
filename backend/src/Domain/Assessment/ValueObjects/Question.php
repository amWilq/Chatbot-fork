<?php

namespace App\Domain\Assessment\ValueObjects;

use App\Shared\Models\EntityToArrayInterface;
use App\Shared\Models\ValueObject;
use App\Shared\Traits\HelperTrait;

readonly class Question extends ValueObject implements EntityToArrayInterface
{

    use HelperTrait;

    protected string $content;

    /** @var string[] $options */
    protected array $options;

    protected string $correctAnswer;

    protected string $explanation;

    protected string $userAnswer;

    protected bool $isCorrect;

    protected string $takenTime;

    public function __construct(
      string $content,
      array $options,
      string $correctAnswer
    ) {
        $this->content = $content;
        $this->options = $options;
        $this->correctAnswer = $correctAnswer;
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

    public function getUserAnswer(): string
    {
        return $this->userAnswer;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function getTakenTime(): string
    {
        return $this->takenTime;
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
          && $this->arraysAreEqual($this->options, $object->options);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [];
        //TODO: Implement toArray() method.
    }

}
