<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Category\Entities\Category;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Persistence\Entities\CategoryEntity;
use App\Infrastructure\Persistence\Entities\PersistenceEntityInterface;
use App\Shared\Models\AggregateRoot;
use Doctrine\Persistence\ManagerRegistry;

class CategoryEntityRepository extends BaseEntityRepository implements CategoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryEntity::class);
    }

    public function find(
        $id,
        $lockMode = null,
        $lockVersion = null,
        bool $raw = false,
    ): null|Category|CategoryEntity {
        $entity = parent::find($id, $lockMode, $lockVersion);

        if ($entity instanceof CategoryEntity && $raw) {
            return $entity;
        }

        return $entity instanceof CategoryEntity ? $this->mapToDomainEntity(
            $entity
        ) : null;
    }

    public function findOneBy(
        array $criteria,
        array $orderBy = null,
        bool $raw = false,
    ): null|Category|CategoryEntity {
        $entity = parent::findOneBy($criteria, $orderBy);

        if ($entity instanceof CategoryEntity && $raw) {
            return $entity;
        }

        return $entity instanceof CategoryEntity ? $this->mapToDomainEntity(
            $entity
        ) : null;
    }

    public function findAll(bool $raw = false): array
    {
        return $this->findBy([], raw: $raw);
    }

    public function findBy(
        array $criteria,
        array $orderBy = null,
        $limit = null,
        $offset = null,
        bool $raw = false,
    ): array {
        $entities = parent::findBy($criteria, $orderBy, $limit, $offset);

        return !$raw ? array_map([$this, 'mapToDomainEntity'], $entities) : $entities;
    }

    public function save(Category|AggregateRoot $aggregateRoot): void
    {
        $this->getEntityManager()->persist(
            CategoryEntity::fromDomainEntity($aggregateRoot, $this->getEntityManager())
        );
        $this->getEntityManager()->flush();
    }

    public function delete(Category|AggregateRoot $aggregateRoot): void
    {
        $this->getEntityManager()->remove(
            CategoryEntity::fromDomainEntity($aggregateRoot, $this->getEntityManager())
        );
        $this->getEntityManager()->flush();
    }

    public function mapToDomainEntity(CategoryEntity|PersistenceEntityInterface $entity
    ): Category {
        return Category::create(
            id: $entity->getId(),
            name: $entity->getName(),
            iconUrl: $entity->getIconUrl(),
        );
    }
}
