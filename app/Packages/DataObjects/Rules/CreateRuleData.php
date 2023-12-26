<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Rules;

use App\Modules\Rules\Models\Rule;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'create_rule_data',
    description: 'Create rule data',
    type: 'object'
)]
class CreateRuleData extends Data
{
    public function __construct(
        #[Property(property: 'title', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $title,
        #[Property(property: 'description', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $description,
        #[Property(property: 'country', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $country,
        #[Property(property: 'date_start', type: 'string', format: 'date')]
        #[
            Required,
            Date
        ]
        public readonly string $date_start,
        #[Property(property: 'date_finish', type: 'string', format: 'date')]
        #[
            Required,
            Date
        ]
        public readonly string $date_finish,
        #[Property(property: 'slug', type: 'string')]
        #[
            Required,
            StringType,
            Unique(Rule::class, 'slug')
        ]
        public readonly string $slug
    ) {
    }
}
