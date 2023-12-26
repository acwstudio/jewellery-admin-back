<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Questionnaire\Answer;

use Illuminate\Support\Collection;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'questionnaire_create_answer_data',
    description: 'Создание ответа опроса',
    type: 'object'
)]
class CreateAnswerData extends Data
{
    public function __construct(
        #[Property(property: 'question_uuid', type: 'string')]
        #[Uuid]
        public readonly string $question_uuid,
        #[Property(property: 'value', type: 'string')]
        public readonly ?string $value,
        #[Property(property: 'comment', type: 'string', nullable: true)]
        public readonly ?string $comment = null,
    ) {
    }

    public static function prepareForPipeline(Collection $properties): Collection
    {
        if (null === $properties->get('value')) {
            $properties->put('value', '');
        }

        return $properties;
    }
}
