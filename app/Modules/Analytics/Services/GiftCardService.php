<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Services;

use App\Modules\Analytics\Repositories\GiftCardRepository;
use Illuminate\Support\Collection;

class GiftCardService
{
    public function __construct(
        protected GiftCardRepository $giftCardRepository
    ) {
    }

    public function getGiftCardCycles(): Collection
    {
        return $this->giftCardRepository->getGiftCardCycles();
    }

    public function eachGiftCardChunk(?array $cycles, callable $callback): void
    {
        $this->giftCardRepository->eachGiftCardChunk($cycles, $callback);
    }
}
