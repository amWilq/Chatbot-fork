<?php

namespace App\Domain\Assessment\Entities;

use App\Domain\Assessment\Enums\AssessmentStatusEnum;
use App\Domain\Assessment\Enums\DifficultiesEnum;
use App\Domain\Assessment\ValueObjects\AssessmentId;
use App\Domain\Category\Entities\Category;
use App\Domain\Language\Entities\Language;
use App\Domain\User\Entities\User;
use App\Shared\Models\AggregateRoot;

final class Assessment extends AggregateRoot
{
    protected AssessmentStatusEnum $status;

    protected \DateTime $startTime;

    protected ?\DateTime $endTime = null;

    protected ?DifficultiesEnum $difficultyAtEnd = null;

    protected ?DifficultiesEnum $currentDifficulty = null;

    protected ?string $feedback = null;

    private function __construct(
        ?string $id,
        ?string $status,
        ?\DateTime $startTime,
        ?\DateTime $endTime,
        ?string $difficultyAtEnd,
        ?string $currentDifficulty,
        ?string $feedback,
        protected User $user,
        protected Category $category,
        protected Language $language,
        protected DifficultiesEnum $difficultyAtStart,
        protected AssessmentType $assessmentType,
    ) {
        $this->id = $id ? AssessmentId::create($id) : AssessmentId::create(
            AggregateRoot::generateId()
        );
        $this->status = $status ? AssessmentStatusEnum::tryFrom(
            $status
        ) : AssessmentStatusEnum::ASSESSMENT_START_SUCCESS;
        $this->startTime = $startTime ?? new \DateTime();
        $this->endTime = $endTime ?: new \DateTime();
        $this->currentDifficulty = $currentDifficulty ? DifficultiesEnum::tryFrom(
            $currentDifficulty
        ) : $this->getDifficultyAtStart();
        $this->difficultyAtEnd = $difficultyAtEnd ? DifficultiesEnum::tryFrom($difficultyAtEnd) : null;
        $this->feedback = $feedback;
    }

    public static function create(
        User $user,
        Category $category,
        Language $language,
        string $difficulty,
        AssessmentType $assessmentType,
        string $id = null,
        string $status = null,
        \DateTime $startTime = null,
        \DateTime $endTime = null,
        string $difficultyAtEnd = null,
        string $currentDifficulty = null,
        string $feedback = null,
    ): self {
        return new self(
            id: $id,
            status: $status,
            user: $user,
            category: $category,
            language: $language,
            startTime: $startTime,
            endTime: $endTime,
            difficultyAtStart: DifficultiesEnum::tryFrom($difficulty),
            currentDifficulty: $currentDifficulty,
            difficultyAtEnd: $difficultyAtEnd,
            assessmentType: $assessmentType,
            feedback: $feedback,
        );
    }

    public function getStatus(): AssessmentStatusEnum
    {
        return $this->status;
    }

    public function setStatus(AssessmentStatusEnum $status): void
    {
        $this->status = $status;
    }

    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    public function getEndTime(): ?\DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTime $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function getDifficultyAtStart(): DifficultiesEnum
    {
        return $this->difficultyAtStart;
    }

    public function getDifficultyAtEnd(): ?DifficultiesEnum
    {
        return $this->difficultyAtEnd;
    }

    public function setDifficultyAtEnd(): void
    {
        $this->difficultyAtEnd = $this->currentDifficulty;
    }

    public function getCurrentDifficulty(): ?DifficultiesEnum
    {
        return $this->currentDifficulty;
    }

    public function setCurrentDifficulty(?string $currentDifficulty): void
    {
        $this->currentDifficulty = DifficultiesEnum::tryFrom(
            $currentDifficulty
        );
    }

    public function getFeedback(): ?string
    {
        return $this->feedback;
    }

    public function setFeedback(string $feedback): void
    {
        $this->feedback = $feedback;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function getAssessmentType(): AssessmentType
    {
        return $this->assessmentType;
    }
}
