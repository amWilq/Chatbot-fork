<?php

namespace App\Domain\Assessment\Entities;

use App\Domain\Assessment\ValueObjects\AssessmentId;
use App\Shared\Models\AggregateRoot;
use App\Shared\Models\ValueObject;
use DateTime;
use JetBrains\PhpStorm\ArrayShape;

final class Assessment extends AggregateRoot
{
    private function __construct(
        protected ValueObject $id,
        protected DateTime $startTime,
        protected DateTime $endTime,
        protected string $feedback,
        protected AssessmentType $assessmentType
    ) {

    }
    public static function create(DateTime $startTime, DateTime $endTime, string $feedback, AssessmentType $assessmentType): self
    {
        return new self(
            id: AssessmentId::create(AggregateRoot::generateId()),
            startTime: $startTime,
            endTime: $endTime,
            feedback: $feedback,
            assessmentType: $assessmentType
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
    public function getFeedback(): string
    {
        return $this->feedback;
    }
    public function getAssessmentType(): AssessmentType
    {
        return $this->assessmentType;
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
