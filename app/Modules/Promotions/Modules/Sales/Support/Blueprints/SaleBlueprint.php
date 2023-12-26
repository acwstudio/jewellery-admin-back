<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Support\Blueprints;

use Carbon\Carbon;

class SaleBlueprint
{
    public function __construct(
        public readonly string $title,
        public readonly string $slug,
    ) {
    }
}
