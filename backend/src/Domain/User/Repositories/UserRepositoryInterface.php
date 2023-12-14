<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\User;
use App\Infrastructure\Persistence\Entities\UserEntity;
use App\Port\Outbound\OutboundPortInterface;

interface UserRepositoryInterface extends OutboundPortInterface
{
    public function find($id, $lockMode = null, $lockVersion = null, bool $raw = false): null|User|UserEntity;

    public function findOneBy(array $criteria, array $orderBy = null, bool $raw = false): null|User|UserEntity;

    /**
     * @return User[]|UserEntity[]
     */
    public function findAll(bool $raw = false): array;

    /**
     * @return User[]|UserEntity[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null, bool $raw = false): array;

    public function save(User $aggregateRoot): void;

    public function delete(User $aggregateRoot): void;

}
