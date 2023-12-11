<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Language\Entities\Language;
use App\Infrastructure\Persistence\Entities\LanguageEntity;
use App\Infrastructure\Persistence\Entities\PersistenceEntityInterface;
use App\Shared\Models\AggregateRoot;
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
        $lockVersion = null,
        bool $raw = false,
    ): null|Language|LanguageEntity {
        $entity = parent::find($id, $lockMode, $lockVersion);

        if ($entity instanceof LanguageEntity && $raw) {
            return $entity;
        }

        return $entity instanceof LanguageEntity ? $this->mapToDomainEntity(
            $entity
        ) : null;
    }

    public function findOneBy(
        array $criteria,
        array $orderBy = null,
        bool $raw = false,
    ): null|Language|LanguageEntity {
        $entity = parent::findOneBy($criteria, $orderBy);

        if ($entity instanceof LanguageEntity && $raw) {
            return $entity;
        }

        return $entity instanceof LanguageEntity ? $this->mapToDomainEntity(
            $entity
        ) : null;
    }

    /**
     * @return Language[]|LanguageEntity[]
     */
    public function findAll(bool $raw = false): array
    {
        return $this->findBy([], raw: $raw);
    }

    /**
     * @return Language[]|LanguageEntity[]
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

    /**
     * @return Language[]|LanguageEntity[]
     */
    public function findByCategoryId(string $categoryId, bool $raw = false): array
    {
        $qb = $this->createQueryBuilder('le')
            ->innerJoin('le.categories', 'c')
            ->where('c.id = :category_id')
            ->setParameter('category_id', $categoryId);

        $entities = $qb->getQuery()->getResult();

        return !$raw ? array_map([$this, 'mapToDomainEntity'], $entities) : $entities;
    }

    public function save(Language|AggregateRoot $aggregateRoot): void
    {
        $this->_em->persist(
            LanguageEntity::fromDomainEntity($aggregateRoot)
        );
        $this->_em->flush();
    }

    public function delete(Language|AggregateRoot $aggregateRoot): void
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
