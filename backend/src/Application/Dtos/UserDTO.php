<?php

namespace App\Application\Dtos;

use App\Domain\User\Entities\User;
use App\Shared\Models\EntityToArrayInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
readonly class UserDTO implements EntityToArrayInterface
{

    private function __construct(
      private string $id,
      private string $deviceId,
      private string $status,
      private string $createdAt
    ) {}

    protected function getId(): string
    {
        return $this->id;
    }

    protected function getDeviceId(): string
    {
        return $this->deviceId;
    }

    protected function getStatus(): string
    {
        return $this->status;
    }

    protected function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public static function fromDomainEntity(User $user): self
    {
        return new self(
          id: $user->getId()->toString(),
          deviceId: $user->getDeviceId()->toString(),
          status: $user->getStatus()->value,
          createdAt: $user->getCreatedAt()->format(DATE_ATOM)
        );
    }

    #[ArrayShape([
      'userId' => 'string',
      'userDeviceId' => 'string',
      'status' => 'string',
      'createdAt' => 'string',
    ])]
    public function toArray(): array
    {
        return [
          'userId' => $this->getId(),
          'userDeviceId' => $this->getDeviceId(),
          'status' => $this->getStatus(),
          'createdAt' => $this->getCreatedAt(),
        ];
    }

}
