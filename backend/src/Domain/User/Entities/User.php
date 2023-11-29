<?php

namespace App\Domain\User\Entities;

use App\Domain\User\ValueObjects\UserDeviceId;
use App\Shared\Models\AggregateRoot;
use App\Domain\User\Enums\UserAccountStatusEnum;
use App\Domain\User\ValueObjects\UserId;

class User extends AggregateRoot
{
    protected UserAccountStatusEnum $status;
    protected \DateTime $createdAt;
    /**
     * User constructor.
     */
    private function __construct(
        protected UserDeviceId $deviceId,
        protected string $name,
    ) {
        $this->id = UserId::create(AggregateRoot::generateId());
        $this->status = UserAccountStatusEnum::ACTIVE;
        $this->createdAt = new \DateTime();
    }
    /**
     * Create a new instance of the current object
     */
    public static function create(string $name, string $deviceId): self
    {
        return new self(
            deviceId: UserDeviceId::create($deviceId),
            name: $name
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
    public function getCreatedAt(): string
    {
        return $this->createdAt->format(DATE_ATOM);
    }
}
