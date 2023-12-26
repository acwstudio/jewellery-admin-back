<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class BreadcrumbController
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/catalog/breadcrumbs/{id}',
        summary: 'Получить хлебные крошки по ID',
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
                description: 'Коллекция хлебных крошек для категории',
                content: new JsonContent(type: 'array', items: new Items(
                    ref: '#/components/schemas/breadcrumb_data'
                ))
            )
        ]
    )]
    public function getBreadcrumbs(int $categoryId): Collection
    {
        return $this->catalogModuleClient->getBreadcrumbs($categoryId);
    }

    #[Get(
        path: '/api/v1/catalog/breadcrumbs/{slug}',
        summary: 'Получить хлебные крошки по слагу',
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'slug',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция хлебных крошек для категории',
                content: new JsonContent(type: 'array', items: new Items(
                    ref: '#/components/schemas/breadcrumb_data'
                ))
            )
        ]
    )]
    public function getBreadcrumbsBySlug(string $slug): Collection
    {
        return $this->catalogModuleClient->getBreadcrumbsBySlug($slug);
    }
}
