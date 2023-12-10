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
      $lockVersion = null
    ): ?User {
        $entity = parent::find(
          $id,
          $lockMode,
          $lockVersion
        );

        return $entity instanceof UserEntity ? $this->mapToDomainEntity(
          $entity
        ) : null;
    }

    public function findOneBy(
      array $criteria,
      ?array $orderBy = null
    ): ?User {
        $entity = parent::findOneBy(
          $criteria,
          $orderBy
        );

        return $entity instanceof UserEntity ? $this->mapToDomainEntity(
          $entity
        ) : null;
    }

    /**
     * @return User[]
     * */
    public function findAll(): array
    {
        return array_map([$this, 'mapToDomainEntity'], parent::findAll());
    }

    /**
     * @return User[]
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
