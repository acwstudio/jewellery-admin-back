<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Questionnaire\Question;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'questionnaire_create_question_data',
    description: 'Создание вопроса опроса',
    type: 'object'
)]
class CreateQuestionData extends Data
{
    public function __construct(
        #[Property(property: 'survey_uuid', type: 'string')]
        public readonly string $survey_uuid,
        #[Property(property: 'value', type: 'string')]
        public readonly string $value,
        #[Property(
            property: 'options',
            type: 'array',
            items: new Items(ref: '#/components/schemas/questionnaire_question_option_data')
        )]
        #[DataCollectionOf(OptionData::class)]
        public readonly DataCollection $options,
        #[Property(property: 'code', type: 'string', nullable: true)]
        public readonly ?string $code = null,
    ) {
    }
}
