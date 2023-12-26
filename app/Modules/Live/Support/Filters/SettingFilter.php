<?php

declare(strict_types=1);

namespace App\Modules\Live\Support\Filters;

use Illuminate\Support\Collection;

class SettingFilter
{
    public function __construct(
        public readonly ?Collection $id = null,
        public readonly ?string $name = null,
    ) {
    }
}
