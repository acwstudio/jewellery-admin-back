<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Services;

use App\Modules\Questionnaire\Models\Question;
use App\Modules\Questionnaire\Models\Survey;
use App\Modules\Questionnaire\Repositories\SurveyRepository;
use App\Modules\Questionnaire\Repositories\QuestionRepository;
use App\Modules\Questionnaire\Support\Blueprints\QuestionBlueprint;
use Illuminate\Support\Collection;

class QuestionService
{
    public function __construct(
        private readonly SurveyRepository $surveyRepository,
        private readonly QuestionRepository $questionRepository
    ) {
    }

    public function getQuestion(string $uuid): ?Question
    {
        return $this->questionRepository->getByUuid($uuid);
    }

    public function getQuestions(Survey|string $survey): Collection
    {
        if (is_string($survey)) {
            $survey = $this->surveyRepository->getByUuid($survey, true);
        }

        return $survey->questions;
    }

    public function createQuestion(QuestionBlueprint $blueprint, Survey|string $survey): Question
    {
        if (is_string($survey)) {
            $survey = $this->surveyRepository->getByUuid($survey, true);
        }

        return $this->questionRepository->create($blueprint, $survey);
    }

    public function updateQuestion(Question|string $question, QuestionBlueprint $blueprint): Question
    {
        if (is_string($question)) {
            $question = $this->questionRepository->getByUuid($question, true);
        }
        $this->questionRepository->update($question, $blueprint);

        return $question->refresh();
    }

    public function deleteQuestion(Question|string $question): void
    {
        if (is_string($question)) {
            $question = $this->questionRepository->getByUuid($question, true);
        }
        $this->questionRepository->delete($question);
    }
}
