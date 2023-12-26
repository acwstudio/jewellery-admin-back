<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Common\Response;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'success_data',
    description: 'Response contains successfulness',
    type: 'object'
)]
class SuccessData extends Data
{
    public function __construct(
        #[Property(property: 'success', type: 'bool')]
        public readonly bool $success = true
    ) {
    }
}
