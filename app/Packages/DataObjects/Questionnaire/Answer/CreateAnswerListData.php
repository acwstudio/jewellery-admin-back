<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Questionnaire\Answer;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[Schema(
    schema: 'questionnaire_create_answer_list_data',
    description: 'Создание ответов опроса',
    type: 'object'
)]
class CreateAnswerListData extends Data
{
    public function __construct(
        #[Property(property: 'survey_uuid', type: 'string')]
        #[Uuid]
        public readonly string $survey_uuid,
        #[Property(
            property: 'answers',
            type: 'array',
            items: new Items(ref: '#/components/schemas/questionnaire_create_answer_data')
        )]
        #[DataCollectionOf(CreateAnswerData::class)]
        public readonly DataCollection $answers,
        #[Property(property: 'identifier', type: 'string', nullable: true)]
        public readonly ?string $identifier = null
    ) {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'answers' => ['required', 'min:1']
        ];
    }
}
