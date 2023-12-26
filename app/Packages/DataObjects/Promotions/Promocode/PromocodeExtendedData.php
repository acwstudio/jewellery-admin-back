<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Promocode;

use App\Modules\Promotions\Models\PromotionBenefit;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'promotions_promocode_extended_data', type: 'object')]
class PromocodeExtendedData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'promocode', type: 'string')]
        public readonly string $promocode,
        #[Property(property: 'is_free_delivery', type: 'boolean', nullable: true)]
        public readonly ?bool $is_free_delivery = null,
    ) {
    }

    public static function fromModel(PromotionBenefit $promotionBenefit): self
    {
        return new self(
            $promotionBenefit->id,
            $promotionBenefit->promocode,
            $promotionBenefit->is_free_delivery
        );
    }
}
