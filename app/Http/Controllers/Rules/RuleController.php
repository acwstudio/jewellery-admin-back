<?php

declare(strict_types=1);

namespace App\Http\Controllers\Rules;

use App\Http\Controllers\Controller;
use App\Modules\Rules\RuleModuleClient;
use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\DataObjects\Rules\CreateRuleData;
use App\Packages\DataObjects\Rules\RuleData;
use App\Packages\DataObjects\Rules\UpdateRuleData;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class RuleController extends Controller
{
    public function __construct(
        protected readonly RuleModuleClient $ruleModuleClient
    ) {
    }
    #[Get(
        path: '/api/v1/rule',
        summary: 'Получить список правил',
        tags: ['Rules'],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция правил',
                content: new JsonContent(
                    type: 'array',
                    items: new Items(
                        ref: '#/components/schemas/rule_data'
                    )
                )
            )
        ]
    )]
    public function index(): Collection
    {
        return $this->ruleModuleClient->getAllRules();
    }

    #[Get(
        path: '/api/v1/rule/{id}',
        summary: 'Получить правило по id',
        tags: ['Rules'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Правило',
                content: new JsonContent(ref: '#/components/schemas/rule_data')
            )
        ]
    )]
    public function show(int $id): RuleData
    {
        return $this->ruleModuleClient->getRuleById($id);
    }

    #[Post(
        path: '/api/v1/rule',
        summary: 'Создание правила',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/create_rule_data')
        ),
        tags: ['Rules'],
        responses: [
            new Response(
                response: 200,
                description: 'Правило',
                content: new JsonContent(ref: '#/components/schemas/rule_data')
            )
        ]
    )]
    public function create(CreateRuleData $ruleData): RuleData
    {
        return $this->ruleModuleClient->createRule($ruleData);
    }

    #[Put(
        path: '/api/v1/rule',
        summary: 'Обновление правила',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/update_rule_data')
        ),
        tags: ['Rules'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Правило',
                content: new JsonContent(ref: '#/components/schemas/rule_data')
            )
        ]
    )]
    public function update(int $id, UpdateRuleData $ruleData): RuleData
    {
        return $this->ruleModuleClient->updateRule($id, $ruleData);
    }

    #[Delete(
        path: '/api/v1/rule/{id}',
        summary: 'Удалить правило',
        tags: ['Rules'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Успешно удалено',
                content: new JsonContent(ref: '#/components/schemas/success_data')
            )
        ]
    )]
    public function destroy(int $id): SuccessData
    {
        return $this->ruleModuleClient->deleteRuleById($id);
    }

    #[Get(
        path: '/api/v1/rule/{slug}',
        summary: 'Получить правило по slug',
        tags: ['Rules'],
        parameters: [
            new PathParameter(
                name: 'slug',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Правило',
                content: new JsonContent(ref: '#/components/schemas/rule_data')
            )
        ]
    )]
    public function slug(string $slug): RuleData
    {
        return $this->ruleModuleClient->getRuleBySlug($slug);
    }
}
