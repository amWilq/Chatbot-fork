<?php

namespace App\Application\Dtos;

use App\Domain\Assessment\Entities\AssessmentType;
use App\Domain\Assessment\Types\FreeTextAssessment\FreeTextAssessment;
use JetBrains\PhpStorm\ArrayShape;

readonly class FreeTextAssessmentDTO extends AssessmentTypeDTO
{
    private function __construct(
        private array $messages,
        private AssessmentTypeDTO $assessmentTypeData
    ) {
        parent::__construct(
            id: $this->assessmentTypeData->getId(),
            name: $this->assessmentTypeData->getName(),
            description: $this->assessmentTypeData->getDescription(),
            difficulties: $this->assessmentTypeData->getDifficulties()
        );
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public static function fromDomainEntity(FreeTextAssessment|AssessmentType $assessmentType) : AssessmentTypeDTO{
        $messages = [];

        foreach ($assessmentType->getMessages() as $message) {
            $messages[] = [
              'sender' => $message->getSender(),
              'message' => $message->getMessage(),
            ];
        }

        return new self(
            messages: $messages,
            assessmentTypeData: AssessmentTypeDTO::fromDomainEntity(
                $assessmentType->getAssessmentType()
            ),
        );
    }
    #[ArrayShape([
        'assessmentTypeId' => 'string',
        'assessmentTypeName' => 'string',
        'messages' => 'array',
    ])]
    public function toArray(): array
    {
        return [
            'assessmentTypeId' => $this->getId(),
            'assessmentTypeName' => $this->getName(),
            'messages' => $this->getMessages(),
        ];
    }
}
