<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Services;

use App\Modules\Questionnaire\Models\CompletedSurvey;
use App\Modules\Questionnaire\Models\Survey;
use App\Modules\Questionnaire\Repositories\CompletedSurveyRepository;
use App\Modules\Questionnaire\Repositories\SurveyRepository;
use App\Modules\Questionnaire\Support\Filters\CompletedSurveyFilter;
use Illuminate\Support\Collection;

class CompletedSurveyService
{
    public function __construct(
        private readonly SurveyRepository $surveyRepository,
        private readonly CompletedSurveyRepository $completedSurveyRepository
    ) {
    }

    public function getCompletedSurvey(string $uuid): CompletedSurvey
    {
        return $this->completedSurveyRepository->getByUuid($uuid, true);
    }

    public function getCompletedSurveyByFilter(CompletedSurveyFilter $filter): Collection
    {
        return $this->completedSurveyRepository->getCollectionByFilter($filter);
    }

    public function createCompletedSurvey(Survey|string $survey, string $identifier): CompletedSurvey
    {
        if (is_string($survey)) {
            $survey = $this->surveyRepository->getByUuid($survey, true);
        }

        return $this->completedSurveyRepository->create($survey, $identifier);
    }
}
