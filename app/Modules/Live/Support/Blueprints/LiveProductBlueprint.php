<?php

declare(strict_types=1);

namespace App\Modules\Live\Support\Blueprints;

use Carbon\Carbon;

class LiveProductBlueprint
{
    public function __construct(
        public readonly int $product_id,
        public readonly int $number,
        public readonly Carbon $started_at,
        public readonly Carbon $expired_at,
        public readonly ?bool $on_live = null,
    ) {
    }
}
