<?php

namespace App\Domain\User\Entities;

use App\Domain\User\ValueObjects\UserDeviceId;
use App\Shared\Models\ValueObject;
use App\Shared\Models\AggregateRoot;
use App\Domain\User\Enums\UserAccountStatusEnum;
use App\Domain\User\ValueObjects\UserId;
use JetBrains\PhpStorm\ArrayShape;

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
    public function getDeviceId(): string
    {
        return $this->deviceId->toString();
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getStatus(): string
    {
        return $this->status->value;
    }
    public function setStatus(string $status): void
    {
        $this->status = UserAccountStatusEnum::tryFrom($status);
    }
    public function getCreatedAt(): string
    {
        return $this->createdAt->format(DATE_ATOM);
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

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        'id' => "string",
        'deviceId' => "string",
        'name' => "string",
        'status' => "string",
        'createdAt' => "string",
    ])]
    public function toArray(): array
    {
        return [
            'id' => $this->getId()->toString(),
            'deviceId' => $this->getDeviceId(),
            'name' => $this->name,
            'status' => $this->getStatus(),
            'createdAt' => $this->getCreatedAt(),
        ];
    }

}
