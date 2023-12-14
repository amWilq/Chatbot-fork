<?php

namespace App\Domain\User\Enums;

enum UserAccountStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';
    case DELETED = 'deleted';
}
