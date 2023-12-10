<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Language\Entities\Language;
use App\Infrastructure\Persistence\Entities\LanguageEntity;
use App\Infrastructure\Persistence\Entities\PersistenceEntityInterface;
use App\Shared\Models\AggregateRoot;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

class LanguageEntityRepository extends BaseEntityRepository
{


    public function __construct(
      ManagerRegistry $registry,
      private readonly CategoryEntityRepository $categoryEntityRepository
    ) {
        parent::__construct($registry, LanguageEntity::class);
    }

    public function find(
      $id,
      $lockMode = null,
      $lockVersion = null
    ): ?Language {
        $entity = parent::find($id, $lockMode, $lockVersion);

        return $entity instanceof LanguageEntity ? $this->mapToDomainEntity(
          $entity
        ) : null;
    }

    public function findOneBy(
      array $criteria,
      ?array $orderBy = null
    ): ?Language {
        $entity = parent::findOneBy($criteria, $orderBy);

        return $entity instanceof LanguageEntity ? $this->mapToDomainEntity(
          $entity
        ) : null;
    }
    /**
     * @return Language[]
     */
    public function findAll(): array
    {
        return array_map([$this, 'mapToDomainEntity'], parent::findAll());
    }

    /**
     * @return Language[]
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


    protected function save(Language|AggregateRoot $aggregateRoot): void
    {
        $this->_em->persist(
          LanguageEntity::fromDomainEntity($aggregateRoot)
        );
        $this->_em->flush();
    }

    protected function delete(Language|AggregateRoot $aggregateRoot): void
    {
        $this->_em->remove(
          LanguageEntity::fromDomainEntity($aggregateRoot)
        );
        $this->_em->flush();
    }

    protected function mapToDomainEntity(
      LanguageEntity|PersistenceEntityInterface $entity
    ): Language {
        return Language::create(
          id: $entity->getId(),
          name: $entity->getName(),
          iconUrl: $entity->getIconUrl(),
          categories: $entity->getCategories()->map(
            function ($category) {
                return $this->categoryEntityRepository->mapToDomainEntity(
                  $category
                );
            }
          )->toArray(),
        );
    }

}
