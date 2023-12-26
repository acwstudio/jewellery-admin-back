<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Vacancies;

use App\Modules\Catalog\Models\Brand;
use App\Modules\Vacancies\Models\Department;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'department_data',
    description: 'Department data',
    type: 'object'
)]
class DepartmentData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'title', type: 'string')]
        public readonly string $title
    ) {
    }

    public static function fromModel(Department $department)
    {
        return new self(
            $department->id,
            $department->title
        );
    }
}
