<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Filters;

use Illuminate\Support\Collection;

class SeoFilter
{
    public function __construct(
        public readonly ?Collection $id = null,
        public readonly ?int $category_id = null,
        public readonly ?string $url = null
    ) {
    }
}
