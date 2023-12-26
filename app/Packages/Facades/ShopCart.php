<?php

declare(strict_types=1);

namespace App\Packages\Facades;

use App\Packages\DataObjects\ShopCart\ShopCartData;
use App\Packages\Services\ShopCartService;
use Illuminate\Support\Facades\Facade;
use Money\Money;

/**
 * @method static ShopCartData getShopCart()
 * @method static Money getTotal()
 */
class ShopCart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ShopCartService::class;
    }
}
