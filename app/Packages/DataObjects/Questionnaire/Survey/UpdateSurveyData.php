<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Questionnaire\Survey;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'questionnaire_update_survey_data',
    description: 'Обновить опрос',
    type: 'object'
)]
class UpdateSurveyData extends Data
{
    public function __construct(
        public readonly string $uuid,
        #[Property(property: 'title', type: 'string')]
        public readonly string $title,
    ) {
    }
}
