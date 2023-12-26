<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Catalog\Feature\CreateFeatureData;
use App\Packages\DataObjects\Catalog\Feature\FeatureData;
use App\Packages\DataObjects\Catalog\Feature\FeatureListData;
use App\Packages\DataObjects\Catalog\Feature\GetListFeatureData;
use App\Packages\DataObjects\Catalog\Feature\UpdateFeatureData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class FeatureController extends Controller
{
    public function __construct(
        protected readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/catalog/feature',
        summary: 'Получить список свойств',
        tags: ['Catalog'],
        parameters: [
            new QueryParameter(
                name: 'pagination',
                description: 'Пагинация',
                required: false,
                schema: new Schema(ref: '#/components/schemas/pagination_data', type: 'object'),
                style: 'deepObject',
                explode: true
            ),
            new QueryParameter(
                name: 'filter',
                description: 'Фильтрация',
                required: false,
                schema: new Schema(ref: '#/components/schemas/catalog_filter_feature_data', type: 'object'),
                style: 'deepObject',
                explode: true
            ),
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Список свойств',
                content: new JsonContent(ref: '#/components/schemas/catalog_feature_list_data')
            )
        ]
    )]
    public function getList(GetListFeatureData $data): FeatureListData
    {
        return $this->catalogModuleClient->getFeatures($data);
    }

    #[Get(
        path: '/api/v1/catalog/feature/{id}',
        summary: 'Получить свойство по ID',
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Свойство',
                content: new JsonContent(ref: '#/components/schemas/catalog_feature_data')
            )
        ]
    )]
    public function get(int $id): FeatureData
    {
        return $this->catalogModuleClient->getFeature($id);
    }

    #[Post(
        path: '/api/v1/catalog/feature',
        summary: 'Создать свойство',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_create_feature_data')
        ),
        tags: ['Catalog'],
        responses: [
            new Response(
                response: 200,
                description: 'Свойство',
                content: new JsonContent(ref: '#/components/schemas/catalog_feature_data')
            )
        ]
    )]
    public function create(CreateFeatureData $data): FeatureData
    {
        return $this->catalogModuleClient->createFeature($data);
    }

    #[Put(
        path: '/api/v1/catalog/feature/{id}',
        summary: 'Обновить свойство по ID',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_update_product_data')
        ),
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Свойство',
                content: new JsonContent(ref: '#/components/schemas/catalog_feature_data')
            )
        ]
    )]
    public function update(UpdateFeatureData $data): FeatureData
    {
        return $this->catalogModuleClient->updateFeature($data);
    }

    #[Delete(
        path: '/api/v1/catalog/feature/{id}',
        summary: 'Удалить свойство по ID',
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function delete(int $id): \Illuminate\Http\Response
    {
        $this->catalogModuleClient->deleteFeature($id);
        return \response('');
    }
}
