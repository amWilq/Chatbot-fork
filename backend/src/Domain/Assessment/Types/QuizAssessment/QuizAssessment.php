<?php

namespace App\Domain\Assessment\Types\QuizAssessment;

use App\Domain\Assessment\Entities\AssessmentType;
use App\Domain\Assessment\Enums\FormatEnum;

final class QuizAssessment extends AssessmentType
{
    protected int $questionCount;

    protected int $correctAnswerCount;

    /** @var QuestionAttempt[] */
    protected array $questionsAttempts;

    /**
     * Class constructor.
     */
    private function __construct(
        ?string $id,
        int $questionCount,
        int $correctAnswerCount,
        array $questionsAttempts,
        protected int $durationInSeconds,
    ) {
        parent::__construct(
            id: $id,
            formatName: FormatEnum::QUIZ->value
        );
        $this->questionCount = $questionCount;
        $this->correctAnswerCount = $correctAnswerCount;
        $this->questionsAttempts = $questionsAttempts;
    }

    public static function create(
        int $durationInSeconds,
        string $id = null,
        int $questionCount = 0,
        int $correctAnswerCount = 0,
        array $questionsAttempts = [],
    ): self {
        return new self(
            id: $id,
            questionCount: $questionCount,
            correctAnswerCount: $correctAnswerCount,
            questionsAttempts: $questionsAttempts,
            durationInSeconds: $durationInSeconds,
        );
    }

    public function getDuration(): int
    {
        return $this->durationInSeconds;
    }

    public function getQuestionCount(): int
    {
        return $this->questionCount;
    }

    public function getCorrectAnswerCount(): int
    {
        return $this->correctAnswerCount;
    }

    public function getQuestionsAttempts(): array
    {
        return $this->questionsAttempts;
    }

    public function setQuestionsAttempts(
        QuestionAttempt $questionAttempt
    ): void {
        $this->questionsAttempts[] = $questionAttempt;
        ++$this->questionCount;
        if ($questionAttempt->isCorrect()) {
            ++$this->correctAnswerCount;
        }
    }

    public function getAssessmentType(): AssessmentType
    {
        return $this->getParent();
    }
}
