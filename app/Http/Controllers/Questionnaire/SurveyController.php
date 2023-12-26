<?php

declare(strict_types=1);

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Questionnaire\Survey\CreateSurveyData;
use App\Packages\DataObjects\Questionnaire\Survey\PublishedSurveyData;
use App\Packages\DataObjects\Questionnaire\Survey\SurveyData;
use App\Packages\DataObjects\Questionnaire\Survey\UpdateSurveyData;
use App\Packages\ModuleClients\QuestionnaireModuleClientInterface;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class SurveyController extends Controller
{
    public function __construct(
        protected readonly QuestionnaireModuleClientInterface $questionnaireModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/questionnaire/survey/{uuid}',
        summary: 'Получить опрос',
        tags: ['Questionnaire'],
        parameters: [
            new PathParameter(
                name: 'uuid',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Опрос',
                content: new JsonContent(ref: '#/components/schemas/questionnaire_survey_data')
            )
        ]
    )]
    public function get(string $uuid): ?SurveyData
    {
        return $this->questionnaireModuleClient->getSurvey($uuid);
    }

    #[Post(
        path: '/api/v1/questionnaire/survey',
        summary: 'Создать опрос',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/questionnaire_create_survey_data')
        ),
        tags: ['Questionnaire'],
        responses: [
            new Response(
                response: 200,
                description: 'Созданный опрос',
                content: new JsonContent(ref: '#/components/schemas/questionnaire_survey_data')
            )
        ]
    )]
    public function create(CreateSurveyData $data): SurveyData
    {
        return $this->questionnaireModuleClient->createSurvey($data);
    }

    #[Put(
        path: '/api/v1/questionnaire/survey/{uuid}',
        summary: 'Обновить опрос',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/questionnaire_update_survey_data')
        ),
        tags: ['Questionnaire'],
        parameters: [
            new PathParameter(
                name: 'uuid',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Обновленный опрос',
                content: new JsonContent(ref: '#/components/schemas/questionnaire_survey_data')
            )
        ]
    )]
    public function update(UpdateSurveyData $data): SurveyData
    {
        return $this->questionnaireModuleClient->updateSurvey($data);
    }

    #[Post(
        path: '/api/v1/questionnaire/survey/send',
        summary: 'Отправить опрос',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/questionnaire_send_survey_data')
        ),
        tags: ['Questionnaire'],
        responses: [
            new Response(
                response: 200,
                description: 'Созданный опрос',
                content: new JsonContent(ref: '#/components/schemas/questionnaire_send_survey_data')
            )
        ]
    )]
    public function send(PublishedSurveyData $data): \Illuminate\Http\Response
    {
        return $this->questionnaireModuleClient->sendPublishedSurvey($data);
    }
}
