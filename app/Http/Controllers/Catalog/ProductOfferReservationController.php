<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Catalog\ProductOffer\Reservation\UpdateProductOfferReservationStatusData;
use App\Packages\DataObjects\Catalog\ProductOffer\Reservation\CreateProductOfferReservationData;
use App\Packages\DataObjects\Catalog\ProductOffer\Reservation\ProductOfferReservationData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class ProductOfferReservationController extends Controller
{
    public function __construct(
        protected readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    #[Post(
        path: '/api/v1/catalog/trade_offer/{id}/reservation',
        summary: 'Создать резервацию торгового предложения продукта',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_create_product_offer_reservation_data')
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
                description: 'Созданная резервация торгового предложения',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_offer_reservation_data')
            )
        ]
    )]
    public function create(CreateProductOfferReservationData $data): ProductOfferReservationData
    {
        return $this->catalogModuleClient->createProductOfferReservation($data);
    }

    #[Put(
        path: '/api/v1/catalog/trade_offer/reservation/{id}/status',
        summary: 'Обновить статус резервации торгового предложения продукта',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_update_product_offer_reservation_status_data')
        ),
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'id',
                description: 'Идентификатор резервации торгового предложения',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Обновленная резервация торгового предложения',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_offer_reservation_data')
            )
        ]
    )]
    public function updateStatus(UpdateProductOfferReservationStatusData $data): ProductOfferReservationData
    {
        return $this->catalogModuleClient->updateProductOfferReservationStatus($data);
    }
}
