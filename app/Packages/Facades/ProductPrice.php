<?php

declare(strict_types=1);

namespace App\Packages\Facades;

use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Services\ProductPriceService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Money\Money;

/**
 * @method static Money getPrice(Collection $prices, array $exclude = [])
 * @method static Money|null getPriceByType(Collection $prices, OfferPriceTypeEnum $type)
 * @see ProductPriceService
 */
class ProductPrice extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ProductPriceService::class;
    }
}
