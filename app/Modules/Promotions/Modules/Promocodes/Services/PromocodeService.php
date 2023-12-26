<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Services;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Modules\Promocodes\Repository\PromocodeRepository;

class PromocodeService
{
    public function __construct(
        private readonly PromocodeRepository $promocodeRepository
    ) {
    }

    public function getPromotionBenefit(string $promocode): ?PromotionBenefit
    {
        return $this->promocodeRepository->getByPromocode($promocode);
    }
}
