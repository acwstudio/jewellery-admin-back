<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Repository;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Modules\Promocodes\Models\PromocodeUsage;
use App\Modules\Promotions\Modules\Promocodes\Support\Filters\PromocodeUsageFilter;
use App\Packages\DataObjects\Promotions\Promocode\CreatePromocodeUsage;
use App\Packages\DataObjects\Promotions\Promocode\UpdatePromocodeUsage;
use App\Packages\Support\FilterQuery\FilterQueryBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class PromocodeUsageRepository
{
    public function getById(int $id, bool $fail = false): PromocodeUsage
    {
        if ($fail) {
            return PromocodeUsage::findOrFail($id);
        }

        return PromocodeUsage::find($id);
    }

    /**
     * @param PromocodeUsageFilter $filter
     * @param bool $fail
     * @return Collection<PromocodeUsage>
     */
    public function getCollectionByFilter(PromocodeUsageFilter $filter, bool $fail = false): Collection
    {
        $query = FilterQueryBuilder::fromQuery(PromocodeUsage::query())->withFilter($filter)->create();

        /** @var Collection<PromocodeUsage> $models */
        $models = $query->get();

        if ($fail && $models->count() === 0) {
            throw (new ModelNotFoundException())->setModel(PromocodeUsage::class);
        }

        return $models;
    }

    public function isUsed(PromotionBenefit $promotionBenefit, string $userId): bool
    {
        $count = $promotionBenefit->promocodeUsages()
            ->getQuery()
            ->whereNotNull('order_id')
            ->where('user_id', $userId)
            ->count();

        return $count !== 0;
    }

    public function getActive(string $shopCartToken): ?PromocodeUsage
    {
        /** @var PromocodeUsage|null $promocodeUsage */
        $promocodeUsage = PromocodeUsage::query()
            ->where([
                'shop_cart_token' => $shopCartToken,
                'is_active' => true
            ])
            ->first();

        return $promocodeUsage;
    }

    public function create(PromotionBenefit $promotionBenefit, CreatePromocodeUsage $data): PromocodeUsage
    {
        $aa = $data->toArray();
        /** @var PromocodeUsage $promocodeUsage */
        $promocodeUsage = $promotionBenefit->promocodeUsages()->create($data->toArray());
        return $promocodeUsage;
    }

    public function update(PromocodeUsage $promocodeUsage, UpdatePromocodeUsage $data): PromocodeUsage
    {
        $promocodeUsage->update($data->toArray());
        return $promocodeUsage->refresh();
    }

    public function delete($shopCardToken): void
    {
        PromocodeUsage::query()->where(['shop_cart_token' => $shopCardToken])->delete();
    }

    public function setOrderId(PromocodeUsage $promocodeUsage, int $orderId): PromocodeUsage
    {
        $promocodeUsage->update([
            'order_id' => $orderId
        ]);
        return $promocodeUsage->refresh();
    }
}
