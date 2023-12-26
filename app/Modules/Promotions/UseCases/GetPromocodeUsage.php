<?php

declare(strict_types=1);

namespace App\Modules\Promotions\UseCases;

use App\Modules\Promotions\Modules\Promocodes\Models\PromocodeUsage;
use App\Modules\Promotions\Modules\Promocodes\Services\PromocodeUsageService;
use App\Modules\Promotions\Modules\Promocodes\Support\Filters\PromocodeUsageFilter;
use App\Packages\DataObjects\Promotions\Filter\FilterPromocodeUsageData;
use App\Packages\DataObjects\Promotions\Promocode\PromocodeUsageData;
use App\Packages\DataObjects\Promotions\Promocode\SetPromocodeUsageOrderId;
use Illuminate\Support\Collection;

class GetPromocodeUsage
{
    public function __construct(
        private readonly PromocodeUsageService $promocodeUsageService
    ) {
    }

    public function getCollectionByFilter(FilterPromocodeUsageData $data): Collection
    {
        $models = $this->promocodeUsageService->getCollectionByFilter(
            new PromocodeUsageFilter(
                $data->promotion_benefit_id,
                $data->shop_cart_token,
                $data->is_active,
                $data->order_id
            )
        );

        return $models->map(
            fn (PromocodeUsage $model) => PromocodeUsageData::fromModel($model)
        );
    }

    public function setOrderId(SetPromocodeUsageOrderId $data): void
    {
        $model = $this->promocodeUsageService->getById($data->promocode_usage_id);
        $this->promocodeUsageService->setOrderId($model, $data->order_id);
    }
}
