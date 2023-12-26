<?php

declare(strict_types=1);

namespace App\Modules\Users\Repositories;

use App\Modules\Users\Contracts\Pipelines\WishlistQueryBuilderPipelineContract;
use App\Modules\Users\Models\User;
use App\Modules\Users\Models\WishlistProduct;
use App\Modules\Users\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class WishlistProductRepository
{
    public function getByUuid(string $uuid, bool $fail = false): ?WishlistProduct
    {
        if ($fail) {
            return WishlistProduct::findOrFail($uuid);
        }

        return WishlistProduct::find($uuid);
    }

    public function getByProductId(User $user, int $productId, bool $fail = false): ?WishlistProduct
    {
        /** @var WishlistProduct|null $model */
        $model = WishlistProduct::query()
            ->where('user_id', '=', $user->user_id)
            ->where('product_id', '=', $productId)
            ->first();

        if ($fail && !$model instanceof WishlistProduct) {
            throw new ModelNotFoundException();
        }

        return $model;
    }

    public function getCount(User $user): int
    {
        return WishlistProduct::query()->where('user_id', '=', $user->user_id)->count();
    }

    public function getList(User $user, Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $query = WishlistProduct::query()->where('user_id', '=', $user->user_id);

        /** @var WishlistQueryBuilderPipelineContract $pipeline */
        $pipeline = app(WishlistQueryBuilderPipelineContract::class);

        /** @var LengthAwarePaginator $paginator */
        $paginator = $pipeline
            ->send($query)
            ->thenReturn()
            ->paginate($pagination->perPage ?? 15, ['*'], 'page', $pagination->page ?? 1);

        if ($fail && $paginator->total() === 0) {
            throw new ModelNotFoundException();
        }

        return $paginator;
    }

    public function getCollection(User $user, bool $fail = false): Collection
    {
        $models = WishlistProduct::query()->where('user_id', '=', $user->user_id)->get();

        if ($fail && $models->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $models;
    }

    public function create(int $productId, User $user): WishlistProduct
    {
        $wishlist = new WishlistProduct([
            'product_id' => $productId
        ]);

        $wishlist->user()->associate($user);
        $wishlist->save();

        return $wishlist;
    }

    public function delete(WishlistProduct $wishlist): void
    {
        $wishlist->delete();
    }
}
