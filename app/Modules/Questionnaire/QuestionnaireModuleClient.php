<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire;

use App\Modules\Questionnaire\Services\QuestionService;
use App\Modules\Questionnaire\Services\SurveyService;
use App\Modules\Questionnaire\Support\Blueprints\QuestionBlueprint;
use App\Modules\Questionnaire\UseCases\CreateAnswers;
use App\Modules\Questionnaire\UseCases\PublishCompletedSurvey;
use App\Modules\Questionnaire\UseCases\SendSurvey;
use App\Packages\DataObjects\Questionnaire\Answer\CreateAnswerListData;
use App\Packages\DataObjects\Questionnaire\Question\CreateQuestionData;
use App\Packages\DataObjects\Questionnaire\Question\QuestionData;
use App\Packages\DataObjects\Questionnaire\Question\UpdateQuestionData;
use App\Packages\DataObjects\Questionnaire\Survey\CreateSurveyData;
use App\Packages\DataObjects\Questionnaire\Survey\PublishedSurveyData;
use App\Packages\DataObjects\Questionnaire\Survey\SurveyData;
use App\Packages\DataObjects\Questionnaire\Survey\UpdateSurveyData;
use App\Packages\ModuleClients\QuestionnaireModuleClientInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

final class QuestionnaireModuleClient implements QuestionnaireModuleClientInterface
{
    public function __construct(
        private readonly SurveyService $surveyService,
        private readonly QuestionService $questionService,
    ) {
    }

    public function getSurvey(string $uuid): SurveyData
    {
        $survey = $this->surveyService->getSurvey($uuid);

        return SurveyData::fromModel($survey);
    }

    public function createSurvey(CreateSurveyData $data): SurveyData
    {
        $survey = $this->surveyService->createSurvey($data->title);

        return SurveyData::fromModel($survey);
    }

    public function updateSurvey(UpdateSurveyData $data): SurveyData
    {
        $survey = $this->surveyService->updateSurvey($data->uuid, $data->title);

        return SurveyData::fromModel($survey);
    }

    public function deleteSurvey(string $uuid): void
    {
        $this->surveyService->deleteSurvey($uuid);
    }

    public function createQuestion(CreateQuestionData $data): QuestionData
    {
        $question = $this->questionService->createQuestion(
            new QuestionBlueprint(
                $data->value,
                $data->options->all(),
                $data->code
            ),
            $data->survey_uuid
        );

        return QuestionData::fromModel($question);
    }

    public function updateQuestion(UpdateQuestionData $data): QuestionData
    {
        $question = $this->questionService->updateQuestion(
            $data->uuid,
            new QuestionBlueprint(
                $data->value,
                $data->options->all(),
                $data->code
            )
        );

        return QuestionData::fromModel($question);
    }

    public function deleteQuestion(string $uuid): void
    {
        $this->questionService->deleteQuestion($uuid);
    }

    public function createAnswerList(CreateAnswerListData $data): void
    {
        App::call(CreateAnswers::class, ['answerListData' => $data]);
    }

    public function publishCompletedSurvey(string $uuid): void
    {
        App::call(PublishCompletedSurvey::class, ['uuid' => $uuid]);
    }

    public function sendPublishedSurvey(PublishedSurveyData $data): Response
    {
        App::call(SendSurvey::class, ['data' => $data]);
        return response('');
    }
}
