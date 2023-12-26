<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Users\Wishlist\GetWishlistData;
use App\Packages\DataObjects\Users\Wishlist\WishlistData;
use App\Packages\DataObjects\Users\Wishlist\WishlistShortData;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class WishlistController extends Controller
{
    public function __construct(
        protected readonly UsersModuleClientInterface $usersModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/user/wishlist',
        summary: 'Получить список избранного',
        tags: ['User'],
        parameters: [
            new QueryParameter(
                name: 'pagination',
                description: 'Пагинация',
                required: false,
                schema: new Schema(ref: '#/components/schemas/pagination_data', type: 'object'),
                style: 'deepObject',
                explode: true
            ),
            new QueryParameter(
                name: 'sort_by',
                description: 'Поле сортировки',
                required: false,
                schema: new Schema(ref: '#/components/schemas/catalog_product_sort_column', type: 'string')
            ),
            new QueryParameter(
                name: 'sort_order',
                description: 'Порядок сортировки',
                required: false,
                schema: new Schema(ref: '#/components/schemas/sort_order', type: 'string')
            ),
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Избранное пользователя',
                content: new JsonContent(ref: '#/components/schemas/users_wishlist_data')
            )
        ]
    )]
    public function getList(GetWishlistData $data): WishlistData
    {
        return $this->usersModuleClient->getWishlist($data);
    }

    #[Get(
        path: '/api/v1/user/wishlist/short',
        summary: 'Получить краткую  информацию по списоку избранного',
        tags: ['User'],
        responses: [
            new Response(
                response: 200,
                description: 'Краткая информация по списоку избранного',
                content: new JsonContent(ref: '#/components/schemas/users_wishlist_short_data')
            )
        ]
    )]
    public function short(): WishlistShortData
    {
        return $this->usersModuleClient->getWishlistShort();
    }

    #[Post(
        path: '/api/v1/user/wishlist/{product_id}',
        summary: 'Добавить в избранное',
        tags: ['User'],
        parameters: [
            new PathParameter(
                name: 'product_id',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function create(int $product_id): \Illuminate\Http\Response
    {
        $this->usersModuleClient->createWishlistProduct($product_id);
        return \response('');
    }

    #[Delete(
        path: '/api/v1/user/wishlist/{product_id}',
        summary: 'Удалить из избранного',
        tags: ['User'],
        parameters: [
            new PathParameter(
                name: 'product_id',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function delete(int $product_id): \Illuminate\Http\Response
    {
        $this->usersModuleClient->deleteWishlistProduct($product_id);
        return \response('');
    }
}
