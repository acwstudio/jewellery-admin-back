<?php

declare(strict_types=1);

namespace App\Packages\Enums\Users;

enum RoleEnum: string
{
    case USER = 'user';
    case ADMIN = 'admin';
}
