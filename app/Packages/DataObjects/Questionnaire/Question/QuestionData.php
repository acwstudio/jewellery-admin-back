<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Questionnaire\Question;

use App\Modules\Questionnaire\Models\Question;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'questionnaire_question_data',
    description: 'Вопрос опроса',
    type: 'object'
)]
class QuestionData extends Data
{
    public function __construct(
        #[Property(property: 'uuid', type: 'string')]
        public readonly string $uuid,
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
        public readonly ?string $code,
    ) {
    }

    public static function fromModel(Question $model): self
    {
        return new self(
            $model->uuid,
            $model->value,
            self::createOptionDataCollection($model),
            $model->code
        );
    }

    private static function createOptionDataCollection(Question $model): DataCollection
    {
        $options = [];
        foreach ($model->options as $option) {
            $options[] = OptionData::from($option);
        }

        return OptionData::collection($options);
    }
}
