<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Promocode;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Packages\Facades\User;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

#[Schema(schema: 'promotions_promocode_data', type: 'object')]
class PromocodeData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'int')]
        public int $id,
        #[Property(property: 'promocode', type: 'string')]
        public string $promocode,
        #[MapName('promotion_external_id')]
        #[Property(property: 'promotion_external_id', type: 'string')]
        public string $promotionExternalId
    ) {
    }

    public static function fromModel(PromotionBenefit $promotionBenefit): self
    {
        return new self(
            $promotionBenefit->id,
            $promotionBenefit->promocode,
            $promotionBenefit->promotion->external_id
        );
    }

    public function includeProperties(): array
    {
        return [
            'id' => User::getUser()->isAdmin(),
            'promotion_external_id' => User::getUser()->isAdmin(),
        ];
    }
}
