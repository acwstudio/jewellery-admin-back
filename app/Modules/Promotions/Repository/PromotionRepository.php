<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Repository;

use App\Modules\Promotions\Models\Promotion;
use App\Packages\DataObjects\Promotions\CreatePromotion;
use App\Packages\DataObjects\Promotions\CreatePromotionPromotion;
use App\Packages\DataObjects\Promotions\UpdatePromotion;

class PromotionRepository
{
    public function getById(int $id, bool $fail = false): ?Promotion
    {
        if ($fail) {
            return Promotion::findOrFail($id);
        }

        return Promotion::find($id);
    }

    public function getByExternalId(string $externalId): ?Promotion
    {
        /** @var Promotion $promotion */
        $promotion = Promotion::query()->where('external_id', $externalId)->first();
        return $promotion;
    }

    public function create(CreatePromotionPromotion $data): Promotion
    {
        $values = $data->toArray();
        /**
         * @var Promotion $promotion
         * @phpstan-ignore-next-line
         */
        $promotion = Promotion::create($values);
        return $promotion;
    }

    public function update(Promotion $promotion, UpdatePromotion $data): Promotion
    {
        $promotion->update($data->toArray());
        return $promotion->refresh();
    }
}
