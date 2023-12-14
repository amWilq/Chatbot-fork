<?php

namespace App\Domain\Assessment\Repositories;

use App\Domain\Assessment\Entities\Assessment;
use App\Infrastructure\Persistence\Entities\AssessmentEntity;
use App\Port\Outbound\OutboundPortInterface;

interface AssessmentRepositoryInterface extends OutboundPortInterface
{
    public function find($id, $lockMode = null, $lockVersion = null, bool $raw = false): null|Assessment|AssessmentEntity;

    public function findOneBy(array $criteria, array $orderBy = null, bool $raw = false): null|Assessment|AssessmentEntity;

    /**
     * @return Assessment[]|AssessmentEntity[]
     */
    public function findAll(bool $raw = false): array;

    /**
     * @return Assessment[]|AssessmentEntity[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null, bool $raw = false): array;

    public function save(Assessment $aggregateRoot): void;

    public function delete(Assessment $aggregateRoot): void;

    public function update(Assessment $aggregateRoot): void;
}
