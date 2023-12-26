<?php

declare(strict_types=1);

namespace App\Http\Controllers\ShopCart;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\ShopCart\ShopCartData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\AddShopCartItemListData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\DeleteShopCartItemListData;
use App\Packages\ModuleClients\ShopCartModuleClientInterface;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;

class ShopCartItemController extends Controller
{
    public function __construct(
        protected readonly ShopCartModuleClientInterface $shopCartModuleClient
    ) {
    }

    #[Put(
        path: '/api/v1/shop_cart/item',
        summary: 'Добавить или обновить торговое предложение в корзине',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/shop_cart_add_item_data')
        ),
        tags: ['Shop Cart'],
        responses: [
            new Response(
                response: 200,
                description: 'Корзина пользователя',
                content: new JsonContent(ref: '#/components/schemas/shop_cart_data')
            )
        ]
    )]
    public function add(AddShopCartItemListData $data): ShopCartData
    {
        return $this->shopCartModuleClient->addShopCartItems($data);
    }

    #[Delete(
        path: '/api/v1/shop_cart/item',
        summary: 'Удалить торговые предложения из корзины',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/shop_cart_delete_item_list_data')
        ),
        tags: ['Shop Cart'],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function delete(DeleteShopCartItemListData $data): \Illuminate\Http\Response
    {
        $this->shopCartModuleClient->deleteShopCartItems($data);
        return \response('');
    }
}
