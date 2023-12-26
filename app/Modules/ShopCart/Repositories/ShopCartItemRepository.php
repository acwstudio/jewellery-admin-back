<?php

declare(strict_types=1);

namespace App\Modules\ShopCart\Repositories;

use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Models\ShopCartItem;
use App\Modules\ShopCart\Support\Blueprints\ShopCartItemBlueprint;

class ShopCartItemRepository
{
    public function getById(int $id, bool $fail = false): ?ShopCartItem
    {
        if ($fail) {
            return ShopCartItem::findOrFail($id);
        }

        return ShopCartItem::find($id);
    }

    public function createOrUpdate(ShopCartItemBlueprint $blueprint, ShopCart $shopCart): ShopCartItem
    {
        $shopCartItem = $shopCart->items()
            ->getQuery()
            ->where('product_offer_id', '=', $blueprint->product_offer_id)
            ->first();

        if ($shopCartItem instanceof ShopCartItem) {
            $this->update($shopCartItem, $blueprint);
            return $shopCartItem->refresh();
        }

        return $this->create($blueprint, $shopCart);
    }

    public function create(ShopCartItemBlueprint $blueprint, ShopCart $shopCart): ShopCartItem
    {
        $shopCartItem = new ShopCartItem([
            'count' => $blueprint->count,
            'selected' => $blueprint->selected
        ]);

        $shopCartItem->shopCart()->associate($shopCart);
        $shopCartItem->product()->associate($blueprint->product_id);
        $shopCartItem->productOffer()->associate($blueprint->product_offer_id);
        $shopCartItem->save();

        return $shopCartItem;
    }

    public function update(ShopCartItem $shopCartItem, ShopCartItemBlueprint $blueprint): bool
    {
        return $shopCartItem->update([
            'count' => $blueprint->count,
            'selected' => $blueprint->selected
        ]);
    }

    public function delete(ShopCartItem $shopCartItem): void
    {
        $shopCartItem->delete();
    }
}
