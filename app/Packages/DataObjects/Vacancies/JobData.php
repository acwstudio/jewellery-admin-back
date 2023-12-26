<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Vacancies;

use App\Modules\Vacancies\Models\Department;
use App\Modules\Vacancies\Models\Job;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'job_data',
    description: 'Job data',
    type: 'object'
)]
class JobData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'title', type: 'string')]
        public readonly string $title,
        #[Property(property: 'salary', type: 'string')]
        public readonly string $salary,
        #[Property(property: 'city', type: 'string')]
        public readonly string $city,
        #[Property(property: 'experience', type: 'string')]
        public readonly string $experience,
        #[Property(property: 'description', type: 'string')]
        public readonly string $description,
        #[Property(property: 'slug', type: 'string')]
        public readonly string $slug,
        #[Property(property: 'department', type: 'object')]
        public readonly DepartmentData $department,
    ) {
    }

    public static function fromModel(Job $job): self
    {
        return new self(
            $job->id,
            $job->title,
            $job->salary,
            $job->city,
            $job->experience,
            $job->description,
            $job->slug,
            DepartmentData::fromModel($job->department)
        );
    }
}
