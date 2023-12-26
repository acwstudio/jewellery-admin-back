<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Support\Blueprints\ProductOfferBlueprint;

class ProductOfferRepository
{
    public function getById(int $id, bool $fail = false): ?ProductOffer
    {
        if ($fail) {
            return ProductOffer::findOrFail($id);
        }

        return ProductOffer::find($id);
    }

    public function create(ProductOfferBlueprint $productOfferBlueprint, Product $product): ProductOffer
    {
        $productOffer = new ProductOffer([
            'size' => $productOfferBlueprint->size,
            'weight' => $productOfferBlueprint->weight
        ]);

        $productOffer->product()->associate($product);
        $productOffer->save();

        return $productOffer;
    }

    public function delete(ProductOffer $productOffer): void
    {
        $productOffer->delete();
    }
}
