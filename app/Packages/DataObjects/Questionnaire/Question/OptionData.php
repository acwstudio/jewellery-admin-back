<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Questionnaire\Question;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'questionnaire_question_option_data',
    description: 'Опции вопроса',
    type: 'object'
)]
class OptionData extends Data
{
    public function __construct(
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'value', type: 'string')]
        public readonly string $value
    ) {
    }
}
