<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Repositories;

use App\Modules\Questionnaire\Models\Answer;
use App\Modules\Questionnaire\Models\Question;
use App\Modules\Questionnaire\Support\Blueprints\AnswerBlueprint;
use App\Modules\Questionnaire\Support\Filters\AnswerFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class AnswerRepository
{
    public function getByUuid(string $uuid, bool $fail = false): ?Answer
    {
        if ($fail) {
            return Answer::findOrFail($uuid);
        }

        return Answer::find($uuid);
    }

    /**
     * @param AnswerFilter $filter
     * @param bool $fail
     * @return Collection<Answer>
     */
    public function getCollectionByFilter(AnswerFilter $filter, bool $fail = false): Collection
    {
        /** @var Collection<Answer> $models */
        $models = Answer::query()
            ->whereHas(
                'question',
                fn (Builder $questionBuilder) => $questionBuilder
                    ->where('survey_uuid', '=', $filter->survey_uuid)
            )
            ->where('identifier', '=', $filter->identifier)
            ->get();

        if ($fail && $models->isEmpty()) {
            throw (new ModelNotFoundException())->setModel(Answer::class);
        }

        return $models;
    }

    public function create(AnswerBlueprint $blueprint, Question $question): Answer
    {
        $model = new Answer([
            'identifier' => $blueprint->identifier,
            'value' => $blueprint->value,
            'comment' => $blueprint->comment
        ]);

        $model->question()->associate($question);
        $model->save();

        return $model;
    }

    public function delete(Answer $model): void
    {
        $model->delete();
    }
}
