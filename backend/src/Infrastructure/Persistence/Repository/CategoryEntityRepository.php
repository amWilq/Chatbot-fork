<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Category\Entities\Category;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Persistence\Entities\PersistenceEntityInterface;
use App\Infrastructure\Persistence\Entities\CategoryEntity;
use App\Shared\Models\AggregateRoot;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\LockMode;

class CategoryEntityRepository extends BaseEntityRepository
  implements CategoryRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryEntity::class);
    }

    public function find(
      $id,
      $lockMode = null,
      $lockVersion = null
    ): ?Category {
        $entity = parent::find($id, $lockMode, $lockVersion);

        return $entity instanceof CategoryEntity ? $this->mapToDomainEntity(
          $entity
        ) : null;
    }

    public function findOneBy(
      array $criteria,
      ?array $orderBy = null
    ): ?Category {
        $entity = parent::findOneBy($criteria, $orderBy);

        return $entity instanceof CategoryEntity ? $this->mapToDomainEntity(
          $entity
        ) : null;
    }

    /**
     * @return Category[]
     */
    public function findAll(): array
    {
        return array_map([$this, 'mapToDomainEntity'], parent::findAll());
    }

    /**
     * @return Category[]
     */
    public function findBy(
      array $criteria,
      ?array $orderBy = null,
      $limit = null,
      $offset = null
    ): array {
        $entities = parent::findBy($criteria, $orderBy, $limit, $offset);

        return array_map([$this, 'mapToDomainEntity'], $entities);
    }

    public function save(Category|AggregateRoot $aggregateRoot): void
    {
        $this->_em->persist(
          CategoryEntity::fromDomainEntity($aggregateRoot)
        );
        $this->_em->flush();
    }

    public function delete(Category|AggregateRoot $aggregateRoot): void
    {
        $this->_em->remove(
          CategoryEntity::fromDomainEntity($aggregateRoot)
        );
        $this->_em->flush();
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
