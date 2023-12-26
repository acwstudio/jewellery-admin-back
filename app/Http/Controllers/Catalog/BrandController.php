<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Catalog\Brand\BrandData;
use App\Packages\DataObjects\Catalog\Brand\CreateBrandData;
use App\Packages\DataObjects\Catalog\Brand\UpdateBrandData;
use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
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

class BrandController extends Controller
{
    public function __construct(
        protected readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/catalog/brand',
        summary: 'Получить список брендов',
        tags: ['Catalog'],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция брендов',
                content: new JsonContent(type: 'array', items: new Items(
                    ref: '#/components/schemas/brand_data'
                ))
            )
        ]
    )]
    public function index(): Collection
    {
        return $this->catalogModuleClient->getAllBrands();
    }

    #[Get(
        path: '/api/v1/catalog/brand/{id}',
        summary: 'Получить бренд по id',
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Бренд',
                content: new JsonContent(ref: '#/components/schemas/brand_data')
            )
        ]
    )]
    public function show(int $id): BrandData
    {
        return $this->catalogModuleClient->getBrandById($id);
    }

    #[Post(
        path: '/api/v1/catalog/brand',
        summary: 'Создание бренда',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/create_brand_data')
        ),
        tags: ['Catalog'],
        responses: [
            new Response(
                response: 200,
                description: 'Бренд',
                content: new JsonContent(ref: '#/components/schemas/brand_data')
            )
        ]
    )]
    public function store(CreateBrandData $brandData): BrandData
    {
        return $this->catalogModuleClient->createBrand($brandData);
    }

    #[Put(
        path: '/api/v1/catalog/brand/{id}',
        summary: 'Обновление бренда',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/update_brand_data')
        ),
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Бренд',
                content: new JsonContent(ref: '#/components/schemas/brand_data')
            )
        ]
    )]
    public function update(int $id, UpdateBrandData $brandData): BrandData
    {
        return $this->catalogModuleClient->updateBrand($id, $brandData);
    }

    #[Delete(
        path: '/api/v1/catalog/brand/{id}',
        summary: 'Удалить бренд',
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Бренд',
                content: new JsonContent(ref: '#/components/schemas/brand_data')
            )
        ]
    )]
    public function destroy(int $id): SuccessData
    {
        return $this->catalogModuleClient->deleteBrandById($id);
    }
}
