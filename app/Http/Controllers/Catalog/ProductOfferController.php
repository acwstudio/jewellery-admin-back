<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Catalog\ProductOffer\CreateProductOfferData;
use App\Packages\DataObjects\Catalog\ProductOffer\ProductOfferData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class ProductOfferController extends Controller
{
    public function __construct(
        protected readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/catalog/trade_offer/{id}',
        summary: 'Получить торговое предложение продукта',
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'id',
                description: 'Идентификатор торгового предложения',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Торговое предложений продукта',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_offer_data')
            )
        ]
    )]
    public function get(int $id): ProductOfferData
    {
        return $this->catalogModuleClient->getProductOffer($id);
    }

    #[Post(
        path: '/api/v1/catalog/trade_offer',
        summary: 'Создать торговое предложение продукта',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_create_product_offer_data')
        ),
        tags: ['Catalog'],
        responses: [
            new Response(
                response: 200,
                description: 'Созданное торговое предложения продукта',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_offer_data')
            )
        ]
    )]
    public function create(CreateProductOfferData $data): ProductOfferData
    {
        return $this->catalogModuleClient->createProductOffer($data);
    }

    #[Delete(
        path: '/api/v1/catalog/trade_offer/{id}',
        summary: 'Удалить торговое предложение продукта по ID',
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'id',
                description: 'Идентификатор торгового предложения',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function delete(int $id): \Illuminate\Http\Response
    {
        $this->catalogModuleClient->deleteProductOffer($id);
        return \response('');
    }
}
