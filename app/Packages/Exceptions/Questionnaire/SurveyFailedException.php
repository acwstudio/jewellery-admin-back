<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Questionnaire;

use App\Packages\Exceptions\DomainException;

class SurveyFailedException extends DomainException
{
    protected $message = 'Survey failed exception';
    protected $code = 'questionnaire_module_survey_failed_exception';
    protected $description = 'Опрос не пройден';
}
