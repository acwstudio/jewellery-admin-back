<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\CreateProductOfferPriceData;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\ProductOfferPriceData;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\UpdateProductOfferPriceIsActiveData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class ProductOfferPriceController extends Controller
{
    public function __construct(
        protected readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    #[Post(
        path: '/api/v1/catalog/trade_offer/{id}/price',
        summary: 'Создать цену торгового предложения продукта',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_create_product_offer_price_data')
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
                description: 'Созданная цена торгового предложения',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_offer_price_data')
            )
        ]
    )]
    public function create(CreateProductOfferPriceData $data): ProductOfferPriceData
    {
        return $this->catalogModuleClient->createProductOfferPrice($data);
    }

    #[Put(
        path: '/api/v1/catalog/trade_offer/{id}/price/{type}',
        summary: 'Обновить активность цены торгового предложения продукта',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_update_product_offer_price_is_active_data')
        ),
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'id',
                description: 'Идентификатор торгового предложения',
                schema: new Schema(type: 'integer')
            ),
            new PathParameter(
                name: 'type',
                description: 'Идентификатор торгового предложения',
                schema: new Schema(ref: '#/components/schemas/OfferPriceTypeEnum')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Обновленная цена торгового предложения',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_offer_price_data')
            )
        ]
    )]
    public function updateIsActive(UpdateProductOfferPriceIsActiveData $data): ProductOfferPriceData
    {
        return $this->catalogModuleClient->updateProductOfferPriceIsActive($data);
    }
}
