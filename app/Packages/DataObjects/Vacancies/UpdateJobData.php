<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Vacancies;

use App\Modules\Vacancies\Models\Department;
use App\Modules\Vacancies\Models\Job;
use Illuminate\Http\Request;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;

#[Schema(
    schema: 'update_job_data',
    description: 'Job data',
    type: 'object'
)]
class UpdateJobData extends Data
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
            Required,
            StringType,
        ]
        public readonly string $slug
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        $request->validate([
            'slug' => [
                \Illuminate\Validation\Rule::unique(Job::class, column: 'slug')
                    ->ignore($request->route('id'))
            ]
        ]);
        return new self(
            $request->input('title'),
            $request->input('salary'),
            $request->input('city'),
            $request->input('experience'),
            $request->input('description'),
            $request->input('department_id'),
            $request->input('slug')
        );
    }
}
