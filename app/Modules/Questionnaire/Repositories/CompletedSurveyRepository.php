<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Repositories;

use App\Modules\Questionnaire\Models\Survey;
use App\Modules\Questionnaire\Models\CompletedSurvey;
use App\Modules\Questionnaire\Support\Filters\CompletedSurveyFilter;
use App\Packages\Support\FilterQuery\FilterQueryBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class CompletedSurveyRepository
{
    public function getByUuid(string $uuid, bool $fail = false): ?CompletedSurvey
    {
        if ($fail) {
            return CompletedSurvey::findOrFail($uuid);
        }

        return CompletedSurvey::find($uuid);
    }

    /**
     * @param CompletedSurveyFilter $filter
     * @param bool $fail
     * @return Collection<CompletedSurvey>
     */
    public function getCollectionByFilter(CompletedSurveyFilter $filter, bool $fail = false): Collection
    {
        $query = FilterQueryBuilder::fromQuery(CompletedSurvey::query())->withFilter($filter)->create();

        /** @var Collection $models */
        $models = $query->get();

        if ($fail && $models->isEmpty()) {
            throw (new ModelNotFoundException())->setModel(CompletedSurvey::class);
        }

        return $models;
    }

    public function create(Survey $survey, string $identifier): CompletedSurvey
    {
        $model = new CompletedSurvey([
            'identifier' => $identifier
        ]);

        $model->survey()->associate($survey);
        $model->save();

        return $model;
    }

    public function delete(CompletedSurvey $model): void
    {
        $model->delete();
    }
}
