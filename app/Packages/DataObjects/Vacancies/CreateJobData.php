<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Vacancies;

use App\Modules\Rules\Models\Rule;
use App\Modules\Vacancies\Models\Department;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;

#[Schema(
    schema: 'create_job_data',
    description: 'Job data',
    type: 'object'
)]
class CreateJobData extends Data
{
    public function __construct(
        #[Property(property: 'title', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $title,
        #[Property(property: 'salary', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $salary,
        #[Property(property: 'city', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $city,
        #[Property(property: 'experience', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $experience,
        #[Property(property: 'description', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $description,
        #[Property(property: 'department_id', type: 'integer')]
        #[
            Required,
            IntegerType,
            Exists(Department::class, 'id')
        ]
        public readonly int $department_id,
        #[Property(property: 'slug', type: 'string')]
        #[
            \Spatie\LaravelData\Attributes\Validation\Required,
            StringType,
            Unique(Rule::class, 'slug')
        ]
        public readonly string $slug
    ) {
    }
}
