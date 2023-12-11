<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\User\Entities\User;
use App\Infrastructure\Persistence\Entities\PersistenceEntityInterface;
use App\Infrastructure\Persistence\Entities\UserEntity;
use App\Shared\Models\AggregateRoot;
use Doctrine\Persistence\ManagerRegistry;

class UserEntityRepository extends BaseEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEntity::class);
    }

    public function find(
        $id,
        $lockMode = null,
        $lockVersion = null,
        bool $raw = false,
    ): null|User|UserEntity {
        $entity = parent::find(
            $id,
            $lockMode,
            $lockVersion
        );

        if ($entity instanceof UserEntity && $raw) {
            return $entity;
        }

        return $entity instanceof UserEntity ? $this->mapToDomainEntity(
            $entity
        ) : null;
    }

    public function findOneBy(
        array $criteria,
        array $orderBy = null,
        bool $raw = false,
    ): null|User|UserEntity {
        $entity = parent::findOneBy(
            $criteria,
            $orderBy
        );

        if ($entity instanceof UserEntity && $raw) {
            return $entity;
        }

        return $entity instanceof UserEntity ? $this->mapToDomainEntity(
            $entity
        ) : null;
    }

    /**
     * @return User[]|UserEntity[]
     * */
    public function findAll(bool $raw = false): array
    {
        return $this->findBy([], raw: $raw);
    }

    /**
     * @return User[]|UserEntity[]
     */
    public function findBy(
        array $criteria,
        array $orderBy = null,
        $limit = null,
        $offset = null,
        bool $raw = false,
    ): array {
        $entities = parent::findBy(
            $criteria,
            $orderBy,
            $limit,
            $offset
        );

        return !$raw ? array_map([$this, 'mapToDomainEntity'], $entities) : $entities;
    }

    protected function save(User|AggregateRoot $aggregateRoot): void
    {
        $this->_em->persist(
            UserEntity::fromDomainEntity($aggregateRoot)
        );
        $this->_em->flush();
    }

    protected function delete(User|AggregateRoot $aggregateRoot): void
    {
        $this->_em->remove(
            UserEntity::fromDomainEntity($aggregateRoot)
        );
        $this->_em->flush();
    }

    protected function mapToDomainEntity(
        UserEntity|PersistenceEntityInterface $entity
    ): User {
        return User::create(
            id: $entity->getId(),
            deviceId: $entity->getDeviceId(),
            name: $entity->getName(),
            status: $entity->getStatus(),
            createdAt: $entity->getCreatedAt()
        );
    }
}
