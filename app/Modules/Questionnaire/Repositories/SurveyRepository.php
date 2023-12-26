<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Repositories;

use App\Modules\Questionnaire\Models\Survey;

class SurveyRepository
{
    public function getByUuid(string $uuid, bool $fail = false): ?Survey
    {
        if ($fail) {
            return Survey::findOrFail($uuid);
        }

        return Survey::find($uuid);
    }

    public function create(string $title): Survey
    {
        $model = new Survey([
            'title' => $title
        ]);

        $model->save();

        return $model;
    }

    public function update(Survey $questionnaire, string $title): void
    {
        $questionnaire->update([
            'title' => $title
        ]);
    }

    public function delete(Survey $model): void
    {
        $model->delete();
    }
}
