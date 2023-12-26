<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'metro_data')]
class MetroData extends Data
{
    public function __construct(
        #[Property('name', type: 'string')]
        public readonly string $name,
        #[Property('line', type: 'string')]
        public readonly string $line
    ) {
    }
}
