<?php

declare(strict_types=1);

namespace App\Modules\Collections\Support\Filters;

use Illuminate\Support\Collection;

class CollectionFilter
{
    public function __construct(
        public readonly ?Collection $id = null,
        public readonly ?string $name = null,
        public readonly ?string $slug = null,
        public readonly ?string $external_id = null,
        public readonly ?bool $is_active = null,
    ) {
    }
}
