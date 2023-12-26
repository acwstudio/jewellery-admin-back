<?php

declare(strict_types=1);

namespace App\Modules\Orders\Repositories;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\Product;
use App\Packages\DataObjects\Orders\CreateOrder\CreateOrderProductData;

class ProductRepository
{
    /** @noinspection PhpIncompatibleReturnTypeInspection */
    public function create(
        Order $order,
        CreateOrderProductData $data
    ): Product {
        return $order->products()->create([
            'product_offer_id' => $data->productOfferId,
            'guid' => $data->guid,
            'sku' => $data->sku,
            'count' => $data->count,
            'price' => $data->price,
            'discount' => $data->discount,
            'amount' => $data->amount,
            'size' => $data->size
        ]);
    }
}
