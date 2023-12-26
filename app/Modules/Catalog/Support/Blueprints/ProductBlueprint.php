<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Blueprints;

use App\Packages\Enums\LiquidityEnum;

class ProductBlueprint
{
    public function __construct(
        public readonly string $sku,
        public readonly string $name,
        public readonly string $summary,
        public readonly string $description,
        public readonly string $manufacture_country,
        public readonly int $rank,
        public readonly ?string $catalog_number = null,
        public readonly ?string $supplier = null,
        public readonly ?LiquidityEnum $liquidity = null,
        public readonly ?float $stamp = null,
        public readonly ?string $meta_title = null,
        public readonly ?string $meta_description = null,
        public readonly ?string $meta_keywords = null,
        public readonly bool $is_active = true,
        public readonly ?bool $is_drop_shipping = null,
        public readonly ?int $popularity = null,
        public readonly ?string $external_id = null,
        public readonly ?string $name_1c = null,
        public readonly ?string $description_1c = null,
        private ?string $slug = null,
    ) {
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }
}
