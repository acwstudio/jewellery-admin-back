<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferReservation;
use App\Modules\Catalog\Models\ProductOfferStock;
use App\Modules\Catalog\Support\Blueprints\ProductOfferReservationBlueprint;
use App\Packages\Enums\Catalog\OfferReservationStatusEnum;
use App\Packages\Exceptions\ModelNotCreatedException;

class ProductOfferReservationRepository
{
    public function getById(int $id, bool $fail = false): ?ProductOfferReservation
    {
        if ($fail) {
            return ProductOfferReservation::findOrFail($id);
        }

        return ProductOfferReservation::find($id);
    }

    public function create(ProductOfferReservationBlueprint $data, ProductOffer $productOffer): ProductOfferReservation
    {
        if (!$this->canCreateReservation($productOffer, $data->count)) {
            throw new ModelNotCreatedException();
        }

        $productOfferReservation = new ProductOfferReservation([
            'count' => $data->count,
            'status' => $data->status
        ]);

        $productOfferReservation->productOffer()->associate($productOffer);
        $productOfferReservation->save();

        return $productOfferReservation;
    }

    public function changeStatus(
        ProductOfferReservation $productOfferReservation,
        OfferReservationStatusEnum $status
    ): bool {
        return $productOfferReservation->update([
            'status' => $status
        ]);
    }

    public function getProductOfferStockAvailable(ProductOffer $productOffer): int
    {
        $allReservationCount = $productOffer->productOfferReservations()
            ->getQuery()
            ->where('status', '=', OfferReservationStatusEnum::PENDING)
            ->sum('count');

        $currentCount = $this->getCurrentProductOfferStock($productOffer)?->count ?? 0;

        return $currentCount === 0 ? 0 : $currentCount - $allReservationCount;
    }

    private function canCreateReservation(ProductOffer $productOffer, int $reservationCount): bool
    {
        $productOfferStockAvailable = $this->getProductOfferStockAvailable($productOffer);

        if ($productOfferStockAvailable >= $reservationCount) {
            return true;
        }

        return false;
    }

    private function getCurrentProductOfferStock(ProductOffer $productOffer): ?ProductOfferStock
    {
        /** @var ProductOfferStock|null $productOfferStock */
        $productOfferStock = $productOffer->productOfferStocks()
            ->getQuery()
            ->where('is_current', '=', true)
            ->get()
            ->first();

        return $productOfferStock;
    }
}
