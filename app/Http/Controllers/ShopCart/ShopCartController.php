<?php

declare(strict_types=1);

namespace App\Http\Controllers\ShopCart;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\ShopCart\ShopCartData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\ShopCartShortInfoData;
use App\Packages\DataObjects\ShopCart\ShopCartShortData;
use App\Packages\ModuleClients\ShopCartModuleClientInterface;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response;

class ShopCartController extends Controller
{
    public function __construct(
        protected readonly ShopCartModuleClientInterface $shopCartModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/shop_cart',
        summary: 'Получить корзину пользователя',
        tags: ['Shop Cart'],
        responses: [
            new Response(
                response: 200,
                description: 'Корзина пользователя',
                content: new JsonContent(ref: '#/components/schemas/shop_cart_data')
            )
        ]
    )]
    public function get(): ShopCartData
    {
        return $this->shopCartModuleClient->getShopCart();
    }

    #[Get(
        path: '/api/v1/shop_cart/short',
        summary: 'Получить краткую информацию по корзине пользователя',
        tags: ['Shop Cart'],
        responses: [
            new Response(
                response: 200,
                description: 'Краткая информация по корзине пользователя',
                content: new JsonContent(ref: '#/components/schemas/shop_cart_short_data')
            )
        ]
    )]
    public function short(): ShopCartShortData
    {
        return $this->shopCartModuleClient->getShortInfo();
    }

    #[Delete(
        path: '/api/v1/shop_cart',
        summary: 'Очистить корзину пользователя',
        tags: ['Shop Cart'],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function delete(): \Illuminate\Http\Response
    {
        $this->shopCartModuleClient->clearShopCart();
        return \response('');
    }
}
