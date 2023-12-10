<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Assessment\Entities\AssessmentType;
use App\Infrastructure\Persistence\Entities\AssessmentTypeEntity;
use App\Infrastructure\Persistence\Entities\PersistenceEntityInterface;
use App\Shared\Models\AggregateRoot;
use Doctrine\Persistence\ManagerRegistry;

class AssessmentTypeEntityRepository extends BaseEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssessmentTypeEntity::class);
    }

    public function find(
        $id,
        $lockMode = null,
        $lockVersion = null
    ): ?AssessmentType {
        $entity = parent::find(
            $id,
            $lockMode,
            $lockVersion
        );

        return $entity instanceof AssessmentType ? $this->mapToDomainEntity(
            $entity
        ) : null;
    }

    public function findOneBy(
        array $criteria,
        ?array $orderBy = null
    ): ?AssessmentType {
        $entity = parent::findOneBy(
            $criteria,
            $orderBy
        );

        return $entity instanceof AssessmentType ? $this->mapToDomainEntity(
            $entity
        ) : null;
    }

    /**
     * @return AssessmentType[]
     */
    public function findAll(): array
    {
        return array_map([$this, 'mapToDomainEntity'], parent::findAll());
    }

    /**
     * @return AssessmentType[]
     */
    public function findBy(
        array $criteria,
        ?array $orderBy = null,
        $limit = null,
        $offset = null
    ): array {
        $entities = parent::findBy(
            $criteria,
            $orderBy,
            $limit,
            $offset
        );

        return array_map([$this, 'mapToDomainEntity'], $entities);
    }

    protected function save(AssessmentType|AggregateRoot $aggregateRoot): void
    {
        $this->_em->persist(
            AssessmentTypeEntity::fromDomainEntity($aggregateRoot)
        );
        $this->_em->flush();
    }

    protected function delete(AssessmentType|AggregateRoot $aggregateRoot): void
    {
        $this->_em->remove(
            AssessmentTypeEntity::fromDomainEntity($aggregateRoot)
        );
        $this->_em->flush();
    }

    protected function mapToDomainEntity(
        AssessmentTypeEntity|PersistenceEntityInterface $entity
    ): AssessmentType {
        return new AssessmentType(
            $entity->getId(),
            $entity->getName()
        );
    }

}
