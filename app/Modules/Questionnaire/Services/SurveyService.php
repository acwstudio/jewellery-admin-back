<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Services;

use App\Modules\Questionnaire\Models\Survey;
use App\Modules\Questionnaire\Repositories\SurveyRepository;

class SurveyService
{
    public function __construct(
        private readonly SurveyRepository $surveyRepository
    ) {
    }

    public function getSurvey(string $uuid): Survey
    {
        return $this->surveyRepository->getByUuid($uuid, true);
    }

    public function createSurvey(string $title): Survey
    {
        return $this->surveyRepository->create($title);
    }

    public function updateSurvey(Survey|string $survey, string $title): Survey
    {
        if (is_string($survey)) {
            $survey = $this->surveyRepository->getByUuid($survey, true);
        }
        $this->surveyRepository->update($survey, $title);

        return $survey->refresh();
    }

    public function deleteSurvey(Survey|string $survey): void
    {
        if (is_string($survey)) {
            $survey = $this->surveyRepository->getByUuid($survey, true);
        }
        $this->surveyRepository->delete($survey);
    }
}
