<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Questionnaire;

use App\Packages\Exceptions\DomainException;

class IdentifierRequiredException extends DomainException
{
    protected $message = 'Identifier required exception';
    protected $code = 'questionnaire_module_identifier_required_exception';
    protected $description = 'Требуется идентификатор';
}
