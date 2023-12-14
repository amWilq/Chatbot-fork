<?php

namespace App\Application\Services;

use App\Application\Dtos\AssessmentTypeDTO;
use App\Domain\Assessment\Repositories\AssessmentTypeRepositoryInterface;
use App\Infrastructure\Persistence\Repository\AssessmentTypeEntityRepository;

class AssessmentTypeService implements AssessmentTypeServiceInterface
{
    public function __construct(
        private readonly AssessmentTypeRepositoryInterface $assessmentTypeEntityRepository
    ) {
    }

    /**
     * @return AssessmentTypeDTO[]
     */
    public function getAllAssessmentTypes(): array
    {
        $types = $this->assessmentTypeEntityRepository->findAll();

        return array_map(
            static fn ($type) => AssessmentTypeDTO::fromDomainEntity($type)->toArray(),
            $types
        );
    }

    /**
     * @return AssessmentTypeDTO[]|null
     */
    public function getAssessmentTypeById(string $id): ?array
    {
        $type = $this->assessmentTypeEntityRepository->find($id);

        return $type ? AssessmentTypeDTO::fromDomainEntity($type)->toArray() : null;
    }
}
