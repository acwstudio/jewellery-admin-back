<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Rules;

use App\Modules\Rules\Models\Rule;
use Carbon\Carbon;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'rule_data',
    description: 'Rule data',
    type: 'object'
)]
class RuleData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'title', type: 'string')]
        public readonly string $title,
        #[Property(property: 'description', type: 'string')]
        public readonly string $description,
        #[Property(property: 'country', type: 'string')]
        public readonly string $country,
        #[Property(property: 'date_start', type: 'string', format: 'date')]
        public readonly string $date_start,
        #[Property(property: 'date_finish', type: 'string', format: 'date')]
        public readonly string $date_finish,
        #[Property(property: 'slug', type: 'string')]
        public readonly string $slug,
    ) {
    }

    public static function fromModel(Rule $rule): self
    {
        return new self(
            $rule->id,
            $rule->title,
            $rule->description,
            $rule->country,
            $rule->date_start->format('Y-m-d'),
            $rule->date_finish->format('Y-m-d'),
            $rule->slug
        );
    }
}
