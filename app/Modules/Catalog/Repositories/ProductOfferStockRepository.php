<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferStock;
use App\Modules\Catalog\Support\Blueprints\ProductOfferStockBlueprint;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductOfferStockRepository
{
    public function getById(int $id, bool $fail = false): ?ProductOfferStock
    {
        if ($fail) {
            return ProductOfferStock::findOrFail($id);
        }

        return ProductOfferStock::find($id);
    }

    public function getCurrentByProductOfferId(int $productOfferId, bool $fail = false): ?ProductOfferStock
    {
        $model = ProductOfferStock::query()
            ->where('is_current', '=', true)
            ->first();

        if ($fail && !$model instanceof ProductOfferStock) {
            throw (new ModelNotFoundException())->setModel(ProductOfferStock::class);
        }

        return $model;
    }

    public function create(ProductOfferStockBlueprint $data, ProductOffer $productOffer): ProductOfferStock
    {
        $productOfferStock = new ProductOfferStock([
            'count' => $data->count,
            'reason' => $data->reason,
            'is_current' => true
        ]);

        $productOffer->productOfferStocks()->getQuery()->update(['is_current' => false]);

        $productOfferStock->productOffer()->associate($productOffer);
        $productOfferStock->save();

        return $productOfferStock;
    }

    public function getCurrent(ProductOffer $productOffer): ?ProductOfferStock
    {
        /** @var ProductOfferStock $productOfferStock */
        $productOfferStock = $productOffer->productOfferStocks()
            ->getQuery()
            ->where('is_current', '=', true)
            ->get()
            ->first();

        return $productOfferStock;
    }
}
