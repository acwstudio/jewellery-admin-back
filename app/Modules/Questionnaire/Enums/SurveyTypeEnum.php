<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Enums;

use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'questionnaire_survey_type_enum',
    type: 'string'
)]
enum SurveyTypeEnum: string
{
    case EXTERNAL = 'external';
}
