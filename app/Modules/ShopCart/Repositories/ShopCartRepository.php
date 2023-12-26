<?php

declare(strict_types=1);

namespace App\Modules\ShopCart\Repositories;

use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Models\ShopCartItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ShopCartRepository
{
    public function getById(int $id, bool $fail = false): ?ShopCart
    {
        if ($fail) {
            return ShopCart::findOrFail($id);
        }

        return ShopCart::find($id);
    }

    public function getByUserId(string $userId, bool $fail = false): ?ShopCart
    {
        $query = ShopCart::query()->where('user_id', '=', $userId);

        if ($fail) {
            /** @var ShopCart $shopCart */
            $shopCart = $query->firstOrFail();
        } else {
            /** @var ShopCart|null $shopCart */
            $shopCart = $query->first();
        }

        return $shopCart;
    }

    public function getByToken(string $token, bool $fail = false): ?ShopCart
    {
        $model = ShopCart::query()
            ->whereNull('user_id')
            ->where('token', '=', $token)
            ->get()
            ->first();

        if ($fail && !$model instanceof ShopCart) {
            throw (new ModelNotFoundException())->setModel(ShopCart::class);
        }

        return $model;
    }

    public function getOrCreate(?string $userId = null, ?string $token = null): ShopCart
    {
        $shopCart = $this->getActual($userId, $token);
        if ($shopCart instanceof ShopCart) {
            return $shopCart;
        }

        return $this->create($userId);
    }

    public function create(?string $userId = null): ShopCart
    {
        $shopCart = new ShopCart([
            'user_id' => $userId
        ]);

        $shopCart->save();

        return $shopCart;
    }

    public function delete(ShopCart $shopCart): void
    {
        $shopCart->delete();
    }

    private function getActual(?string $userId = null, ?string $token = null): ?ShopCart
    {
        if (empty($token) && empty($userId)) {
            return null;
        }

        $shopCartByUserId = !empty($userId) ? $this->getByUserId($userId) : null;
        $shopCartByToken = !empty($token) ? $this->getByToken($token) : null;

        if (
            $shopCartByToken instanceof ShopCart
            && !empty($userId)
        ) {
            $this->updateUserId($shopCartByToken, $userId);
            return $shopCartByToken->refresh();
        }

        return $shopCartByUserId ?? $shopCartByToken;
    }

    private function updateUserId(ShopCart $shopCart, string $userId): void
    {
        /** @var ShopCart|null $shopCartOld */
        $shopCartOld = ShopCart::query()
            ->where('id', '!=', $shopCart->id)
            ->where('user_id', '=', $userId)
            ->first();

        if ($shopCartOld instanceof ShopCart) {
            $this->moveShopCartItems($shopCart, $shopCartOld->items()->get());
            $shopCartOld->delete();
        }

        $shopCart->update(['user_id' => $userId]);
    }

    private function moveShopCartItems(ShopCart $shopCart, Collection $moveItems): void
    {
        $items = $shopCart->items()->get();

        /** @var ShopCartItem $moveItem */
        foreach ($moveItems as $moveItem) {
            if ($items->where('product_offer_id', '=', $moveItem->product_offer_id)->isNotEmpty()) {
                continue;
            }

            $moveItem->shopCart()->associate($shopCart);
            $moveItem->save();
        }
    }
}
