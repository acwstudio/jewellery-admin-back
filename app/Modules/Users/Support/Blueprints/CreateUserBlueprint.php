<?php

declare(strict_types=1);

namespace App\Modules\Users\Support\Blueprints;

use App\Packages\Support\PhoneNumber;

class CreateUserBlueprint
{
    public function __construct(
        public readonly PhoneNumber $phone,
        public readonly string $name = "Пользователь",
        public readonly ?string $email = null,
        public readonly ?string $password = null
    ) {
    }
}
