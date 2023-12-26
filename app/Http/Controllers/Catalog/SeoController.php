<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Catalog\Seo\CreateSeoData;
use App\Packages\DataObjects\Catalog\Seo\Filter\SeoFilterData;
use App\Packages\DataObjects\Catalog\Seo\SeoData;
use App\Packages\DataObjects\Catalog\Seo\UpdateSeoData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class SeoController extends Controller
{
    public function __construct(
        protected readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/catalog/seo',
        summary: 'Получить список SEO',
        tags: ['Catalog'],
        parameters: [
            new QueryParameter(
                name: 'id',
                schema: new Schema(type: 'array', items: new Items(type: 'integer'))
            ),
            new QueryParameter(
                name: 'url',
                schema: new Schema(type: 'string')
            ),
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция SEO',
                content: new JsonContent(type: 'array', items: new Items(
                    ref: '#/components/schemas/catalog_seo_data'
                ))
            )
        ]
    )]
    public function getList(SeoFilterData $data): Collection
    {
        return $this->catalogModuleClient->getSeoCollection($data);
    }

    #[Get(
        path: '/api/v1/catalog/seo/{id}',
        summary: 'Получить SEO по ID',
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
    public function get(int $id): SeoData
    {
        return $this->catalogModuleClient->getSeo($id);
    }

    #[Post(
        path: '/api/v1/catalog/seo',
        summary: 'Создать SEO',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_create_seo_data')
        ),
        tags: ['Catalog'],
        responses: [
            new Response(
                response: 200,
                description: 'SEO',
                content: new JsonContent(ref: '#/components/schemas/catalog_seo_data')
            )
        ]
    )]
    public function create(CreateSeoData $data): SeoData
    {
        return $this->catalogModuleClient->createSeo($data);
    }

    #[Put(
        path: '/api/v1/catalog/seo/{id}',
        summary: 'Обновить SEO по ID',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_update_seo_data')
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
                description: 'SEO',
                content: new JsonContent(ref: '#/components/schemas/catalog_seo_data')
            )
        ]
    )]
    public function update(UpdateSeoData $data): SeoData
    {
        return $this->catalogModuleClient->updateSeo($data);
    }

    #[Delete(
        path: '/api/v1/catalog/seo/{id}',
        summary: 'Удалить SEO по ID',
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
        $this->catalogModuleClient->deleteSeo($id);
        return \response('');
    }
}
