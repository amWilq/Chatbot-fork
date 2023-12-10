<?php

namespace App\Application\Dtos;

use App\Domain\Assessment\Entities\Assessment;
use App\Shared\Models\EntityToArrayInterface;
use App\Shared\Traits\HelperTrait;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;
use Symfony\Component\ErrorHandler\Error\ClassNotFoundError;

#[Immutable]
readonly class AssessmentDTO implements EntityToArrayInterface
{
    use HelperTrait;

    private function __construct(
        private string $id,
        private string $state,
        private string $userDeviceId,
        private string $categoryId,
        private string $languageId,
        private string $difficultyAtStart,
        private string $difficultyAtEnd,
        private string $startTime,
        private string $endTime,
        private string $feedback,
        private AssessmentTypeDTO $assessmentDetails
    ) {
    }

    protected function getId(): string
    {
        return $this->id;
    }

    protected function getState(): string
    {
        return $this->state;
    }

    protected function getUserDeviceId(): string
    {
        return $this->userDeviceId;
    }

    protected function getCategoryId(): string
    {
        return $this->categoryId;
    }

    protected function getLanguageId(): string
    {
        return $this->languageId;
    }

    protected function getDifficultyAtStart(): string
    {
        return $this->difficultyAtStart;
    }

    protected function getDifficultyAtEnd(): string
    {
        return $this->difficultyAtEnd;
    }

    protected function getStartTime(): string
    {
        return $this->startTime;
    }

    protected function getEndTime(): string
    {
        return $this->endTime;
    }

    protected function getFeedback(): string
    {
        return $this->feedback;
    }

    protected function getAssessmentDetails(): AssessmentTypeDTO
    {
        return $this->assessmentDetails;
    }

    public static function fromDomainEntity(
        Assessment $assessment
    ): self {
        $assessmentTypeName = $assessment->getAssessmentType()->getName();

        $DtoClass = self::convertNameToClassName(
            $assessmentTypeName,
            'AssessmentDTO'
        );

        if (!class_exists($DtoClass)) {
            throw new ClassNotFoundError("Class '$DtoClass' not found.");
        }

        return new self(
            id: $assessment->getId()->toString(),
            state: $assessment->getStatus()->name,
            userDeviceId: $assessment->getUser()->getDeviceId()->toString(),
            categoryId: $assessment->getCategory()->getId()->toString(),
            languageId: $assessment->getLanguage()->getId()->toString(),
            difficultyAtStart: $assessment->getDifficultyAtStart()->value,
            difficultyAtEnd: $assessment->getDifficultyAtEnd()->value,
            startTime: $assessment->getStartTime()->format(DATE_ATOM),
            endTime: $assessment->getEndTime()->format(DATE_ATOM),
            feedback: $assessment->getFeedback(),
            assessmentDetails: $DtoClass::fromDomainEntity(
                $assessment->getAssessmentType()
            ),
        );
    }

    #[ArrayShape([
        'assessmentId' => 'string',
        'assessmentState' => 'string',
        'userDeviceId' => 'string',
        'CategoryId' => 'string',
        'LanguageId' => 'string',
        'difficultyAtStart' => 'string',
        'difficultyAtEnd' => 'string',
        'startTime' => 'string',
        'endTime' => 'string',
        'feedback' => 'string',
        'assessmentDetails' => 'array',
    ])]
    public function toArray(): array
    {
        return [
            'assessmentId' => $this->getId(),
            'assessmentState' => $this->getState(),
            'userDeviceId' => $this->getUserDeviceId(),
            'CategoryId' => $this->getCategoryId(),
            'LanguageId' => $this->getLanguageId(),
            'difficultyAtStart' => $this->getDifficultyAtStart(),
            'difficultyAtEnd' => $this->getDifficultyAtEnd(),
            'startTime' => $this->getStartTime(),
            'endTime' => $this->getEndTime(),
            'feedback' => $this->getFeedback(),
            'assessmentDetails' => $this->getAssessmentDetails()->toArray(),
        ];
    }

}
