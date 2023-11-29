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
        protected int $durationInSeconds,
    ) {
        parent::__construct(FormatEnum::QUIZ->value);
    }

    public static function create(int $durationInSeconds):self
    {
        return new self(
            durationInSeconds: $durationInSeconds,
        );
    }
    public function getDuration(): string
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
    public function setQuestionsAttempts(QuestionAttempt $questionAttempt):void
    {
        $this->questionsAttempts[] = $questionAttempt;
    }

}
