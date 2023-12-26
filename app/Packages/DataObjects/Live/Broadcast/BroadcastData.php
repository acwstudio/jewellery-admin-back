<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Live\Broadcast;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'live_broadcast_data', type: 'object')]
class BroadcastData extends Data
{
    public function __construct(
        #[Property(property: 'url', type: 'string', nullable: true)]
        public readonly ?string $url = null,
    ) {
    }
}
