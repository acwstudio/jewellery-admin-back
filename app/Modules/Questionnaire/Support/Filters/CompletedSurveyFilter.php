<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Support\Filters;

use Illuminate\Support\Collection;

class CompletedSurveyFilter
{
    public function __construct(
        public readonly ?Collection $uuid = null,
        public readonly ?string $survey_uuid = null,
        public readonly ?string $identifier = null
    ) {
    }
}
