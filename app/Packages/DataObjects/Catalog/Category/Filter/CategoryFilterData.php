<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Category\Filter;

use App\Packages\DataCasts\CollectionCast;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class CategoryFilterData extends Data
{
    public function __construct(
        #[WithCast(CollectionCast::class)]
        public readonly ?Collection $id = null,
        public readonly ?string $external_id = null
    ) {
    }
}
