<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Catalog\ProductFeature\CreateProductFeatureData;
use App\Packages\DataObjects\Catalog\ProductFeature\ProductFeatureData;
use App\Packages\DataObjects\Catalog\ProductFeature\UpdateProductFeatureData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class ProductFeatureController extends Controller
{
    public function __construct(
        protected readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    #[Post(
        path: '/api/v1/catalog/product_feature',
        summary: 'Создать свойство продукта',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_create_product_feature_data')
        ),
        tags: ['Catalog'],
        responses: [
            new Response(
                response: 200,
                description: 'Свойство продукта',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_feature_data')
            )
        ]
    )]
    public function create(CreateProductFeatureData $data): ProductFeatureData
    {
        return $this->catalogModuleClient->createProductFeature($data);
    }

    #[Put(
        path: '/api/v1/catalog/product_feature/{uuid}',
        summary: 'Обновить свойство по UUID',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_update_product_feature_data')
        ),
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'uuid',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Свойство',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_feature_data')
            )
        ]
    )]
    public function update(UpdateProductFeatureData $data): ProductFeatureData
    {
        return $this->catalogModuleClient->updateProductFeature($data);
    }

    #[Delete(
        path: '/api/v1/catalog/product_feature/{uuid}',
        summary: 'Удалить свойство продукта по UUID',
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'uuid',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function delete(string $uuid): \Illuminate\Http\Response
    {
        $this->catalogModuleClient->deleteProductFeature($uuid);
        return \response('');
    }
}
