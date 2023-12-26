<?php

declare(strict_types=1);

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Questionnaire\Answer\CreateAnswerListData;
use App\Packages\ModuleClients\QuestionnaireModuleClientInterface;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;

class AnswerController extends Controller
{
    public function __construct(
        protected readonly QuestionnaireModuleClientInterface $questionnaireModuleClient
    ) {
    }

    #[Post(
        path: '/api/v1/questionnaire/answer',
        summary: 'Создать ответы опроса',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/questionnaire_create_answer_list_data')
        ),
        tags: ['Questionnaire'],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function create(CreateAnswerListData $data): \Illuminate\Http\Response
    {
        $this->questionnaireModuleClient->createAnswerList($data);
        return \response('');
    }
}
