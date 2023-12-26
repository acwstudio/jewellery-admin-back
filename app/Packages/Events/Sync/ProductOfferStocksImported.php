<?php

declare(strict_types=1);

namespace App\Packages\Events\Sync;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductOfferStocksImported
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

}
