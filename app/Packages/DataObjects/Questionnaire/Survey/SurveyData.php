<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Questionnaire\Survey;

use App\Modules\Questionnaire\Models\Question;
use App\Modules\Questionnaire\Models\Survey;
use App\Packages\DataObjects\Questionnaire\Question\QuestionData;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'questionnaire_survey_data',
    description: 'Опрос',
    type: 'object'
)]
class SurveyData extends Data
{
    public function __construct(
        #[Property(property: 'uuid', type: 'string')]
        public readonly string $uuid,
        #[Property(property: 'title', type: 'string')]
        public readonly string $title,
        #[Property(
            property: 'questions',
            type: 'array',
            items: new Items(ref: '#/components/schemas/questionnaire_question_data')
        )]
        #[DataCollectionOf(QuestionData::class)]
        public readonly DataCollection $questions,
    ) {
    }

    public static function fromModel(Survey $model): self
    {
        return new self(
            $model->uuid,
            $model->title,
            self::createQuestionDataCollection($model)
        );
    }

    private static function createQuestionDataCollection(Survey $model): DataCollection
    {
        $questions = $model->questions->sortBy('order');

        $items = $questions->map(
            fn (Question $question) => QuestionData::fromModel($question)
        );

        return QuestionData::collection($items->flatten());
    }
}
