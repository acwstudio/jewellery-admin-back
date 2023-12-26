<?php

declare(strict_types=1);

namespace App\Modules\Promotions\UseCases;

use App\Modules\Promotions\Modules\Promocodes\Models\PromocodePrice;
use App\Modules\Promotions\Modules\Promocodes\Services\PromocodePriceService;
use App\Modules\Promotions\Modules\Promocodes\Support\Filters\PromocodePriceFilter;
use App\Packages\DataObjects\Promotions\Promocode\Price\PromocodePriceData;
use Illuminate\Support\Collection;

class GetPromocodePrice
{
    public function __construct(
        private readonly PromocodePriceService $promocodePriceService
    ) {
    }

    public function getCollectionByFilter(PromocodePriceFilter $filter): Collection
    {
        $models = $this->promocodePriceService->getPromocodePrices($filter);

        return $models->map(
            fn (PromocodePrice $model) => PromocodePriceData::fromModel($model)
        );
    }
}
