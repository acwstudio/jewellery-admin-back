<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery;

use App\Modules\Delivery\Models\CurrierDelivery;
use App\Packages\DataTransformers\MoneyTransformer;
use Money\Money;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(schema: 'currier_delivery_data', type: 'object')]
class CurrierDeliveryData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'string')]
        public readonly string $id,
        #[Property(property: 'carrier_id', type: 'string')]
        #[MapName('carrier_id')]
        public readonly string $carrierId,
        #[Property(property: 'price', type: 'integer')]
        #[WithTransformer(MoneyTransformer::class)]
        public readonly Money $price,
        #[Property(property: 'address', type: 'string')]
        public readonly string $address,
        #[Property(property: 'currier_delivery_address', type: 'string')]
        public readonly CurrierDeliveryAddressData $currierDeliveryAddress
    ) {
    }

    public static function fromModel(CurrierDelivery $currierDelivery): self
    {
        return new self(
            $currierDelivery->id,
            $currierDelivery->carrier_id,
            $currierDelivery->price,
            $currierDelivery->address->address,
            CurrierDeliveryAddressData::from($currierDelivery->address)
        );
    }
}
