<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Vacancies;

use App\Modules\Vacancies\Models\Department;
use Illuminate\Http\UploadedFile;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\File;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

#[Schema(
    schema: 'create_job_apply_data',
    description: 'Job data',
    type: 'object'
)]
class CreateVacancyApplyData extends Data
{
    public function __construct(
        #[Property(property: 'city', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $city,
        #[Property(property: 'department', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $department,
        #[Property(property: 'job', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $job,
        #[Property(property: 'surname', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $surname,
        #[Property(property: 'name', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $name,
        #[Property(property: 'citizenship', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $citizenship,
        #[Property(property: 'phone', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $phone,
        #[Property(property: 'resume', type: 'string')]
        #[File]
        public readonly ?UploadedFile $resume,
    ) {
    }
}
