<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Services;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Modules\Promocodes\Models\PromocodeUsage;
use App\Modules\Promotions\Modules\Promocodes\Repository\PromocodeUsageRepository;
use App\Modules\Promotions\Modules\Promocodes\Support\Filters\PromocodeUsageFilter;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use Illuminate\Support\Collection;

class PromocodeUsageService
{
    public function __construct(
        private readonly UsersModuleClientInterface $usersModuleClient,
        private readonly PromocodeUsageRepository $promocodeUsageRepository
    ) {
    }

    public function getById(int $id): PromocodeUsage
    {
        return $this->promocodeUsageRepository->getById($id, true);
    }

    public function getCollectionByFilter(PromocodeUsageFilter $filter): Collection
    {
        return $this->promocodeUsageRepository->getCollectionByFilter($filter);
    }

    public function isUsed(PromotionBenefit $promocode): bool
    {
        $user = $this->usersModuleClient->getUser();

        return $this->promocodeUsageRepository->isUsed(
            $promocode,
            $user->user_id
        );
    }

    public function setOrderId(PromocodeUsage|int $promocodeUsage, int $orderId): PromocodeUsage
    {
        if (is_int($promocodeUsage)) {
            $promocodeUsage = $this->promocodeUsageRepository->getById($promocodeUsage);
        }

        return $this->promocodeUsageRepository->setOrderId($promocodeUsage, $orderId);
    }
}
