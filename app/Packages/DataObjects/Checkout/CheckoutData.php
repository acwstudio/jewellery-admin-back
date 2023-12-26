<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Checkout;

use App\Packages\DataObjects\Delivery\SavedAddressData;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

#[Schema(schema: 'checkout_data', type: 'object')]
class CheckoutData extends Data
{
    /**
     * @param Collection<SavedAddressData> $savedAddresses
     */
    public function __construct(
        #[MapName('personal_data')]
        #[Property(
            property: 'personal_data',
            ref: '#/components/schemas/checkout_personal_data',
            type: 'object',
        )]
        public readonly PersonalData $personalData,
        #[MapName('order_data')]
        #[Property(
            property: 'order_data',
            ref: '#/components/schemas/checkout_order_data',
            type: 'object',
        )]
        public readonly OrderData $orderData,
        #[MapName('saved_addresses')]
        #[Property(
            property: 'saved_addresses',
            type: 'array',
            items: new Items(
                ref: '#/components/schemas/delivery_saved_address_data',
            )
        )]
        public readonly Collection $savedAddresses,
        #[MapName('saved_pvz')]
        #[Property(
            property: 'saved_pvz',
            type: 'array',
            items: new Items(
                ref: '#/components/schemas/saved_pvz_data',
            )
        )]
        public readonly Collection $savedPvz
    ) {
    }
}
