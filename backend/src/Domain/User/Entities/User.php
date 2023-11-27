<?php

namespace App\Domain\User\Entities;

use App\Domain\User\ValueObjects\UserDeviceId;
use App\Shared\Models\ValueObject;
use App\Shared\Models\AggregateRoot;
use App\Domain\User\Enums\UserAccountStatusEnum;

class User extends AggregateRoot
{
    /**
     * User constructor.
     */
    private function __construct(
        protected ValueObject $id,
        protected string $name,
        protected UserAccountStatusEnum $status,
        protected \DateTime $createdAt
    ) {

    }

    /**
     * Create a new instance of the current object
     */
    public static function create(string $name): self
    {
        return new self(
            id: UserDeviceId::create(AggregateRoot::generateId()),
            name: $name,
            status: UserAccountStatusEnum::ACTIVE,
            createdAt: new \DateTime()
        );
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }

}
