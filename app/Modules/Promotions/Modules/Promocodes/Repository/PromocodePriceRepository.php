<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Repository;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Modules\Promocodes\Models\PromocodePrice;
use App\Modules\Promotions\Modules\Promocodes\Support\Filters\PromocodePriceFilter;
use App\Packages\DataObjects\Promotions\Promocode\CreatePromocodePrice;
use App\Packages\Support\FilterQuery\FilterQueryBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class PromocodePriceRepository
{
    /**
     * @param PromocodePriceFilter $filter
     * @param bool $fail
     * @return Collection<PromocodePrice>
     */
    public function getCollectionByFilter(PromocodePriceFilter $filter, bool $fail = false): Collection
    {
        $query = FilterQueryBuilder::fromQuery(PromocodePrice::query())->withFilter($filter)->create();

        /** @var Collection<PromocodePrice> $models */
        $models = $query->get();

        if ($fail && $models->count() === 0) {
            throw (new ModelNotFoundException())->setModel(PromocodePrice::class);
        }

        return $models;
    }

    public function create(PromotionBenefit $promotionBenefit, CreatePromocodePrice $data): PromocodePrice
    {
        /** @var \App\Modules\Promotions\Modules\Promocodes\Models\PromocodePrice $promocodePrice */
        $promocodePrice = $promotionBenefit->promocodePrice()->create($data->toArray());
        return $promocodePrice;
    }

    public function delete(PromotionBenefit $promotionBenefit, $shopCartToken): void
    {
        $promotionBenefit->promocodePrice()->getQuery()->where('shop_cart_token', $shopCartToken)->delete();
    }
}
