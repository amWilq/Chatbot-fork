<?php

namespace App\Domain\Assessment\Entities;

use App\Domain\Assessment\Enums\AssessmentStatusEnum;
use App\Domain\Assessment\Enums\DifficultiesEnum;
use App\Domain\Assessment\ValueObjects\AssessmentId;
use App\Domain\Category\ValueObjects\CategoryId;
use App\Domain\Language\ValueObjects\LanguageId;
use App\Shared\Models\AggregateRoot;
use App\Shared\Models\ValueObject;
use DateTime;
use JetBrains\PhpStorm\ArrayShape;
use App\Domain\User\ValueObjects\UserDeviceId;

final class Assessment extends AggregateRoot
{
    private function __construct(
        protected ValueObject $id,
        protected AssessmentStatusEnum $status,
        protected UserDeviceId $userDeviceId,
        protected CategoryId $categoryId,
        protected LanguageId $languageId,
        protected DifficultiesEnum $difficultyAtStart,
        protected ?DifficultiesEnum $difficultyAtEnd,
        protected DateTime $startTime,
        protected ?DateTime $endTime,
        protected ?string $feedback,
        protected ?AssessmentType $assessmentType
    ) {

    }
    public static function create(string $userDeviceId, string $categoryId, string $languageId, string $difficulty): self
    {
        return new self(
            id: AssessmentId::create(AggregateRoot::generateId()),
            status: AssessmentStatusEnum::ASSESSMENT_START_SUCCESS,
            userDeviceId: UserDeviceId::create($userDeviceId),
            categoryId: CategoryId::create($categoryId),
            languageId: LanguageId::create($languageId),
            difficultyAtStart: DifficultiesEnum::tryFrom($difficulty),
            difficultyAtEnd: null,
            startTime: new DateTime(),
            endTime: null,
            feedback: null,
            assessmentType: null
        );
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
    public function setDifficultyAtEnd(string $difficultyAtEnd): void
    {
        $this->difficultyAtEnd = DifficultiesEnum::tryFrom($difficultyAtEnd);
    }
    public function getFeedback(): string
    {
        return $this->feedback;
    }
    public function setFeedback(string $feedback): void
    {
        $this->feedback = $feedback;
    }

    #[ArrayShape([
      'id' => "AssessmentId",
      'startTime' => "string",
      'endTime' => "string",
      'feedback' => "string",
      'assessmentType' => "AssessmentType"
    ])] public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'startTime' => $this->getStartTime(),
            'endTime' => $this->getEndTime(),
            'feedback' => $this->feedback,
            'assessmentType' => $this->assessmentType->toArray()
        ];
    }


}
