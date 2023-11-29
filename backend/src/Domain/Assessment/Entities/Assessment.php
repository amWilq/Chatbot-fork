<?php

namespace App\Domain\Assessment\Entities;

use App\Domain\Assessment\Enums\AssessmentStatusEnum;
use App\Domain\Assessment\Enums\DifficultiesEnum;
use App\Domain\Assessment\ValueObjects\AssessmentId;
use App\Domain\Category\ValueObjects\CategoryId;
use App\Domain\Language\ValueObjects\LanguageId;
use App\Domain\User\ValueObjects\UserDeviceId;
use App\Shared\Models\AggregateRoot;
use DateTime;

final class Assessment extends AggregateRoot
{
    protected AssessmentStatusEnum $status;
    protected DateTime $startTime;
    protected ?DateTime $endTime = null;
    protected ?DifficultiesEnum $difficultyAtEnd = null;
    protected ?DifficultiesEnum $currentDifficulty = null;
    protected ?string $feedback = null;
    private function __construct(
        protected UserDeviceId $userDeviceId,
        protected CategoryId $categoryId,
        protected LanguageId $languageId,
        protected DifficultiesEnum $difficultyAtStart,
        protected AssessmentType $assessmentType,
    ) {
        $this->id = AssessmentId::create(AggregateRoot::generateId());
        $this->status = AssessmentStatusEnum::ASSESSMENT_START_SUCCESS;
        $this->startTime = new DateTime();
        $this->currentDifficulty = $this->difficultyAtStart;
    }
    public static function create(
        UserDeviceId $userDeviceId,
        CategoryId $categoryId,
        LanguageId $languageId,
        string $difficulty,
        AssessmentType $assessmentType
    ): self {
        return new self(
            userDeviceId: $userDeviceId,
            categoryId: $categoryId,
            languageId: $languageId,
            difficultyAtStart: DifficultiesEnum::tryFrom($difficulty),
            assessmentType: $assessmentType
        );
    }
    public function getStatus(): AssessmentStatusEnum
    {
        return $this->status;
    }
    public function setStatus(AssessmentStatusEnum $status):void
    {
        $this->status = $status;
    }

    public function getStartTime(): string
    {
        return $this->startTime->format(DATE_ISO8601_EXPANDED);
    }
    public function getEndTime(): string
    {
        return $this->endTime->format(DATE_ISO8601_EXPANDED);
    }
    public function setEndTime(int $timestamp):void
    {
        $this->endTime->setTimestamp($timestamp);
    }
    public function getDifficultyAtStart(): DifficultiesEnum
    {
        return $this->difficultyAtStart;
    }
    public function getDifficultyAtEnd(): DifficultiesEnum
    {
        return $this->difficultyAtEnd;
    }
    public function setDifficultyAtEnd(): void
    {
        $this->difficultyAtEnd = $this->currentDifficulty;
    }
    public function getCurrentDifficulty(): DifficultiesEnum
    {
        return $this->currentDifficulty;
    }
    public function setCurrentDifficulty(string $currentDifficulty): void
    {
        $this->currentDifficulty = DifficultiesEnum::tryFrom($currentDifficulty);
    }
    public function getFeedback(): string
    {
        return $this->feedback;
    }
    public function setFeedback(string $feedback): void
    {
        $this->feedback = $feedback;
    }
    public function getUserDeviceId(): UserDeviceId
    {
        return $this->userDeviceId;
    }
    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }
    public function getLanguageId(): LanguageId
    {
        return $this->languageId;
    }
    public function getAssessmentType(): AssessmentType
    {
        return $this->assessmentType;
    }
}
