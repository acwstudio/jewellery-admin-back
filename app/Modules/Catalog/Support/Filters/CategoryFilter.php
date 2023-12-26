<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Filters;

use Illuminate\Support\Collection;

class CategoryFilter
{
    public function __construct(
        public readonly ?Collection $id = null,
        public readonly ?string $external_id = null,
        public readonly ?string $title = null,
        public readonly ?string $slug = null,
        public readonly ?int $parent_id = null,
    ) {
    }
}
