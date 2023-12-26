<?php

declare(strict_types=1);

namespace App\Modules\Users\Support\Blueprints;

use App\Modules\Users\Enums\SexTypeEnum;

class UpdateUserBlueprint
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $surname = null,
        public readonly ?string $patronymic = null,
        public readonly ?SexTypeEnum $sex = null,
        public readonly ?string $birth_date = null,
        public readonly ?string $email = null,
        public readonly ?string $password = null,
        public readonly ?string $old_password = null
    ) {
    }
}
