<?php

namespace App\Infrastructure\Persistence\Entities;

use App\Domain\User\Entities\User;
use App\Infrastructure\Persistence\Repository\UserEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserEntityRepository::class)]
#[ORM\Table(name: 'users')]
class UserEntity implements PersistenceEntityInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(name: 'user_id', type: Types::STRING)]
    private string $id;

    #[ORM\Column(name: 'device_id', type: Types::STRING)]
    private string $deviceId;

    #[ORM\Column(name: 'name', type: Types::STRING)]
    private string $name;

    #[ORM\Column(name: 'status', type: Types::STRING)]
    private string $status;

    #[ORM\Column(name: 'created_at', type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTime $createdAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getDeviceId(): string
    {
        return $this->deviceId;
    }

    public function setDeviceId(string $deviceId): void
    {
        $this->deviceId = $deviceId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public static function fromDomainEntity(User $user): self
    {
        $userEntity = new self();

        $userEntity->setId($user->getId()->toString());
        $userEntity->setDeviceId($user->getDeviceId()->toString());
        $userEntity->setName($user->getName());
        $userEntity->setStatus($user->getStatus()->value);
        $userEntity->setCreatedAt($user->getCreatedAt());

        return $userEntity;
    }

}
