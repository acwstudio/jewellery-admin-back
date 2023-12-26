<?php

declare(strict_types=1);

namespace App\Packages\Events\Sync;

use App\Packages\DataObjects\Catalog\ProductOffer\Price\ImportProductOfferPriceData;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ProductOfferPricesImported
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly array $message
    ) {
    }
}
