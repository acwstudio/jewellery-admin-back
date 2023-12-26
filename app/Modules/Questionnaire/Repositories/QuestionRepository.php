<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Repositories;

use App\Modules\Questionnaire\Models\Question;
use App\Modules\Questionnaire\Models\Survey;
use App\Modules\Questionnaire\Support\Blueprints\QuestionBlueprint;
use Illuminate\Support\Collection;

class QuestionRepository
{
    public function getByUuid(string $uuid, bool $fail = false): ?Question
    {
        if ($fail) {
            return Question::findOrFail($uuid);
        }

        return Question::find($uuid);
    }

    public function create(QuestionBlueprint $blueprint, Survey $questionnaire): Question
    {
        $model = new Question([
            'value' => $blueprint->value,
            'options' => $blueprint->options,
            'code' => $blueprint->code
        ]);

        $model->survey()->associate($questionnaire);
        $model->save();

        return $model;
    }

    public function update(Question $question, QuestionBlueprint $blueprint): void
    {
        $question->update([
            'value' => $blueprint->value,
            'options' => $blueprint->options,
            'code' => $blueprint->code
        ]);
    }

    public function delete(Question $model): void
    {
        $model->delete();
    }
}
