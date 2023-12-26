<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Packages\DataObjects\Questionnaire\Answer\AnswerData;
use App\Packages\DataObjects\Questionnaire\Answer\CreateAnswerData;
use App\Packages\DataObjects\Questionnaire\Answer\CreateAnswerListData;
use App\Packages\DataObjects\Questionnaire\Question\CreateQuestionData;
use App\Packages\DataObjects\Questionnaire\Question\QuestionData;
use App\Packages\DataObjects\Questionnaire\Question\UpdateQuestionData;
use App\Packages\DataObjects\Questionnaire\Survey\CreateSurveyData;
use App\Packages\DataObjects\Questionnaire\Survey\PublishedSurveyData;
use App\Packages\DataObjects\Questionnaire\Survey\SurveyData;
use App\Packages\DataObjects\Questionnaire\Survey\UpdateSurveyData;
use Illuminate\Http\Response;

interface QuestionnaireModuleClientInterface
{
    public function getSurvey(string $uuid): SurveyData;

    public function createSurvey(CreateSurveyData $data): SurveyData;

    public function updateSurvey(UpdateSurveyData $data): SurveyData;

    public function deleteSurvey(string $uuid): void;

    public function createQuestion(CreateQuestionData $data): QuestionData;

    public function updateQuestion(UpdateQuestionData $data): QuestionData;

    public function deleteQuestion(string $uuid): void;

    public function createAnswerList(CreateAnswerListData $data): void;

    public function publishCompletedSurvey(string $uuid): void;

    public function sendPublishedSurvey(PublishedSurveyData $data): Response;
}
