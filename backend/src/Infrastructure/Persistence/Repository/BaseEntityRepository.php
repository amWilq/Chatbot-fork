<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Infrastructure\Persistence\Entities\PersistenceEntityInterface;
use App\Shared\Models\AggregateRoot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class BaseEntityRepository extends ServiceEntityRepository
{
    abstract protected function mapToDomainEntity(
        PersistenceEntityInterface $entity
    ): AggregateRoot;

    abstract protected function save(
        AggregateRoot $aggregateRoot
    ): void;

    abstract protected function delete(
        AggregateRoot $aggregateRoot
    ): void;
}
