<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Questionnaire\Survey;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'questionnaire_create_survey_data',
    description: 'Создать опрос',
    type: 'object'
)]
class CreateSurveyData extends Data
{
    public function __construct(
        #[Property(property: 'title', type: 'string')]
        public readonly string $title,
    ) {
    }
}
