<?php

declare(strict_types=1);

namespace App\Modules\Live\Support\Blueprints;

use App\Modules\Live\Enums\SettingNameEnum;

class SettingBlueprint
{
    public function __construct(
        public readonly SettingNameEnum $name,
        public readonly ?string $value
    ) {
    }
}
