<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer\Size;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'no_size_offer_data',
    description: 'Нет моего размера',
    type: 'object'
)]
class NoSizeOfferData extends Data
{
    public function __construct(
        #[Property(property: 'full_name', type: 'string')]
        public readonly string $full_name,
        #[Property(property: 'email', type: 'string')]
        public readonly string $email,
        #[Property(property: 'phone', type: 'string')]
        public readonly string $phone,
        #[Property(property: 'product_name', type: 'string')]
        public readonly string $product_name,
        #[Property(property: 'product_sku', type: 'string')]
        public readonly string $product_sku,
        #[Property(property: 'product_size', type: 'string', nullable: true)]
        public readonly ?string $product_size,
    ) {
    }
}
