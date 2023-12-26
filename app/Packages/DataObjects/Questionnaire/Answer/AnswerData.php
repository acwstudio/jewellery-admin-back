<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Questionnaire\Answer;

use App\Modules\Questionnaire\Models\Answer;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'questionnaire_answer_data',
    description: 'Ответ опроса',
    type: 'object'
)]
class AnswerData extends Data
{
    public function __construct(
        #[Property(property: 'uuid', type: 'string')]
        public readonly string $uuid,
        #[Property(property: 'question_uuid', type: 'string')]
        public readonly string $question_uuid,
        #[Property(property: 'value', type: 'string')]
        public readonly string $value,
        #[Property(property: 'comment', type: 'string', nullable: true)]
        public readonly ?string $comment = null,
    ) {
    }

    public static function fromModel(Answer $model): self
    {
        return new self(
            $model->uuid,
            $model->question_uuid,
            $model->value,
            $model->comment
        );
    }
}
