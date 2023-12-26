<?php

declare(strict_types=1);

namespace App\Modules\Promotions\UseCases;

use App\Modules\Promotions\Modules\Promocodes\Services\PromocodeBenefitService;
use App\Modules\Promotions\Modules\Promocodes\Services\PromocodeConditionService;
use App\Modules\Promotions\Modules\Promocodes\Services\PromocodeService;
use App\Packages\Exceptions\Promotions\ApplyPromocodeException;
use App\Packages\Exceptions\Promotions\PromocodeNotFoundException;
use Throwable;

class ApplyPromocode
{
    public function __construct(
        private readonly PromocodeService $promocodeService,
        private readonly PromocodeConditionService $promocodeConditionService,
        private readonly PromocodeBenefitService $promocodeBenefitService
    ) {
    }

    /**
     * @throws ApplyPromocodeException
     * @throws PromocodeNotFoundException|Throwable
     */
    public function __invoke(string $promocode): void
    {
        $promotionBenefit = $this->promocodeService->getPromotionBenefit($promocode);

        if ($promotionBenefit === null) {
            throw new PromocodeNotFoundException();
        }

        if ($this->promocodeConditionService->verify($promotionBenefit) === false) {
            throw new ApplyPromocodeException();
        }

        $this->promocodeBenefitService->apply($promotionBenefit);
    }
}
