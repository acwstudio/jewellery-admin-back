<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Vacancies;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

#[Schema(
    schema: 'update_department_data',
    description: 'Department data',
    type: 'object'
)]
class UpdateDepartmentData extends Data
{
    public function __construct(
        #[Property(property: 'title', type: 'string')]
        #[
            Required,
            StringType
        ]
        public readonly string $title
    ) {
    }
}
