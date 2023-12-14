<?php

namespace App\Domain\User\Entities;

use App\Domain\User\Enums\UserAccountStatusEnum;
use App\Domain\User\ValueObjects\UserDeviceId;
use App\Domain\User\ValueObjects\UserId;
use App\Shared\Models\AggregateRoot;

class User extends AggregateRoot
{
    protected UserAccountStatusEnum $status;

    protected \DateTime $createdAt;

    /**
     * User constructor.
     */
    private function __construct(
        ?string $id,
        ?string $status,
        ?\DateTime $createdAt,
        protected UserDeviceId $deviceId,
        protected string $name,
    ) {
        $this->id = $id ? UserId::create($id) : UserId::create(
            AggregateRoot::generateId()
        );
        $this->status = $status ? UserAccountStatusEnum::tryFrom(
            $status
        ) : UserAccountStatusEnum::ACTIVE;
        $this->createdAt = $createdAt ?? new \DateTime();
    }

    /**
     * Create a new instance of the current object.
     */
    public static function create(
        string $name,
        string $deviceId,
        string $id = null,
        string $status = null,
        \DateTime $createdAt = null
    ): self {
        return new self(
            id: $id,
            status: $status,
            createdAt: $createdAt,
            deviceId: UserDeviceId::create($deviceId),
            name: $name,
        );
    }

    public function getDeviceId(): UserDeviceId
    {
        return $this->deviceId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): UserAccountStatusEnum
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = UserAccountStatusEnum::tryFrom($status);
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
