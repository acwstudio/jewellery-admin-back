<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Blueprints;

class ProductOfferBlueprint
{
    public function __construct(
        public readonly ?string $size = null,
        public readonly ?string $weight = null
    ) {
    }
}
