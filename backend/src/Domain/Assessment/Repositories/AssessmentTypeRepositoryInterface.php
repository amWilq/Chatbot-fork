<?php

namespace App\Domain\Assessment\Repositories;

use App\Domain\Assessment\Entities\AssessmentType;
use App\Infrastructure\Persistence\Entities\AssessmentTypeEntity;
use App\Port\Outbound\OutboundPortInterface;

interface AssessmentTypeRepositoryInterface extends OutboundPortInterface
{
    public function find($id, $lockMode = null, $lockVersion = null, bool $raw = false): null|AssessmentType|AssessmentTypeEntity;

    public function findOneBy(array $criteria, array $orderBy = null, bool $raw = false): null|AssessmentType|AssessmentTypeEntity;

    /**
     * @return AssessmentType[]|AssessmentTypeEntity[]
     */
    public function findAll(bool $raw = false): array;

    /**
     * @return AssessmentType[]|AssessmentTypeEntity[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null, bool $raw = false): array;

    public function save(AssessmentType $aggregateRoot): void;

    public function delete(AssessmentType $aggregateRoot): void;

}
