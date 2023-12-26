<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Catalog\ProductOffer\Stock\CreateProductOfferStockData;
use App\Packages\DataObjects\Catalog\ProductOffer\Stock\ProductOfferStockData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class ProductOfferStockController extends Controller
{
    public function __construct(
        protected readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    #[Post(
        path: '/api/v1/catalog/trade_offer/{id}/stock',
        summary: 'Создать остаток торгового предложения продукта',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_create_product_offer_stock_data')
        ),
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
                description: 'Созданный остаток торгового предложения',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_offer_stock_data')
            )
        ]
    )]
    public function create(CreateProductOfferStockData $data): ProductOfferStockData
    {
        return $this->catalogModuleClient->createProductOfferStock($data);
    }
}
