<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions;

use _PHPStan_dcc7b7cff\Symfony\Contracts\Service\Attribute\Required;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'apply_promocode_data', type: 'object')]
class ApplyPromocodeData extends Data
{
    public function __construct(
        #[Property('promocode', type: 'string')]
        #[Required]
        public readonly string $promocode
    ) {
    }
}
