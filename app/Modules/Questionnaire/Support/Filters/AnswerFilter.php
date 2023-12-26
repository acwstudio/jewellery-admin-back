<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Support\Filters;

class AnswerFilter
{
    public function __construct(
        public readonly string $survey_uuid,
        public readonly string $identifier
    ) {
    }
}
