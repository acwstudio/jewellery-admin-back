<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Validator;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Modules\Promocodes\Services\PromocodeUsageService;
use App\Modules\Promotions\Modules\Promocodes\Support\Validator\PromocodeValidatorInterface;
use App\Packages\Exceptions\Promotions\OneUserPerUserPromocodeException;
use App\Packages\ModuleClients\UsersModuleClientInterface;

class OneUsePerUserPromocodeValidator implements PromocodeValidatorInterface
{
    public function __construct(
        private readonly PromocodeUsageService $promocodeUsageService
    ) {
    }

    /**
     * @throws OneUserPerUserPromocodeException
     */
    public function validate(PromotionBenefit $promocode): bool
    {
        if ($this->promocodeUsageService->isUsed($promocode)) {
            throw new OneUserPerUserPromocodeException();
        }
        return true;
    }
}
