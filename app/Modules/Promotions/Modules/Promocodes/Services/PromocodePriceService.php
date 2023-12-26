<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Services;

use App\Modules\Promotions\Modules\Promocodes\Repository\PromocodePriceRepository;
use App\Modules\Promotions\Modules\Promocodes\Support\Filters\PromocodePriceFilter;
use Illuminate\Support\Collection;

class PromocodePriceService
{
    public function __construct(
        private readonly PromocodePriceRepository $promocodePriceRepository
    ) {
    }

    public function getPromocodePrices(PromocodePriceFilter $filter): Collection
    {
        return $this->promocodePriceRepository->getCollectionByFilter($filter);
    }
}
