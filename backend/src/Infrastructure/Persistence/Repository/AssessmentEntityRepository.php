<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Assessment\Repositories\AssessmentRepositoryInterface;
use App\Infrastructure\Persistence\Entities\AssessmentEntity;
use App\Shared\Models\AggregateRoot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AssessmentEntityRepository extends ServiceEntityRepository implements AssessmentRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssessmentEntity::class);
    }

    /**
     * @inheritDoc
     */
    public function save(AggregateRoot $aggregateRoot): void
    {
        // TODO: Implement save() method.
    }

    /**
     * @inheritDoc
     */
    public function delete(AggregateRoot $aggregateRoot): void
    {
        // TODO: Implement delete() method.
    }

}
