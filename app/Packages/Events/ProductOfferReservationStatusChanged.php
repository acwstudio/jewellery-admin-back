<?php

declare(strict_types=1);

namespace App\Packages\Events;

use App\Packages\Enums\Catalog\OfferReservationStatusEnum;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductOfferReservationStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        private readonly int $productOfferId,
        private readonly int $reservationCount,
        private readonly OfferReservationStatusEnum $oldStatus,
        private readonly OfferReservationStatusEnum $newStatus
    ) {
    }

    public function getProductOfferId(): int
    {
        return $this->productOfferId;
    }

    public function getReservationCount(): int
    {
        return $this->reservationCount;
    }

    public function getOldStatus(): OfferReservationStatusEnum
    {
        return $this->oldStatus;
    }

    public function getNewStatus(): OfferReservationStatusEnum
    {
        return $this->newStatus;
    }
}
