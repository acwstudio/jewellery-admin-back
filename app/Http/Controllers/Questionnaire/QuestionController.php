<?php

declare(strict_types=1);

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Questionnaire\Question\CreateQuestionData;
use App\Packages\DataObjects\Questionnaire\Question\QuestionData;
use App\Packages\DataObjects\Questionnaire\Question\UpdateQuestionData;
use App\Packages\ModuleClients\QuestionnaireModuleClientInterface;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class QuestionController extends Controller
{
    public function __construct(
        protected readonly QuestionnaireModuleClientInterface $questionnaireModuleClient
    ) {
    }

    #[Post(
        path: '/api/v1/questionnaire/question',
        summary: 'Создать вопрос опроса',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/questionnaire_create_question_data')
        ),
        tags: ['Questionnaire'],
        responses: [
            new Response(
                response: 200,
                description: 'Созданный вопрос опрос',
                content: new JsonContent(ref: '#/components/schemas/questionnaire_question_data')
            )
        ]
    )]
    public function create(CreateQuestionData $data): QuestionData
    {
        return $this->questionnaireModuleClient->createQuestion($data);
    }

    #[Put(
        path: '/api/v1/questionnaire/question/{uuid}',
        summary: 'Обновить вопрос опроса',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/questionnaire_update_question_data')
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
                description: 'Обновленный вопрос опроса',
                content: new JsonContent(ref: '#/components/schemas/questionnaire_survey_data')
            )
        ]
    )]
    public function update(UpdateQuestionData $data): QuestionData
    {
        return $this->questionnaireModuleClient->updateQuestion($data);
    }

    #[Delete(
        path: '/api/v1/questionnaire/question/{uuid}',
        summary: 'Удалить вопрос опроса',
        tags: ['Questionnaire'],
        parameters: [
            new PathParameter(
                name: 'uuid',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function delete(string $uuid): \Illuminate\Http\Response
    {
        $this->questionnaireModuleClient->deleteQuestion($uuid);
        return \response('');
    }
}
