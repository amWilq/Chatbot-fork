<?php

namespace App\Domain\Language\Repositories;

use App\Domain\Language\Entities\Language;
use App\Infrastructure\Persistence\Entities\LanguageEntity;
use App\Port\Outbound\OutboundPortInterface;

interface LanguageRepositoryInterface extends OutboundPortInterface
{

    public function find($id, $lockMode = null, $lockVersion = null, bool $raw = false): null|Language|LanguageEntity;

    public function findOneBy(array $criteria, array $orderBy = null, bool $raw = false): null|Language|LanguageEntity;

    /**
     * @return Language[]|LanguageEntity[]
     */
    public function findAll(bool $raw = false): array;

    /**
     * @return Language[]|LanguageEntity[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null, bool $raw = false): array;

    public function save(Language $aggregateRoot): void;

    public function delete(Language $aggregateRoot): void;
}
