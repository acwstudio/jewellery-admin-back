<?php

declare(strict_types=1);

namespace App\Modules\Analytics;

use App\Modules\Analytics\Services\GiftCardService;
use App\Packages\DataObjects\Analytics\GiftCard\AnalyticsGiftCardData;
use App\Packages\ModuleClients\AnalyticsModuleClientInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Money\Money;

final class AnalyticsModuleClient implements AnalyticsModuleClientInterface
{
    public function __construct(
        private readonly GiftCardService $giftCardService
    ) {
    }

    /**
     * @return Collection<string>
     */
    public function getGiftCardCycles(): Collection
    {
        return $this->giftCardService->getGiftCardCycles();
    }

    /**
     * @param array|null $cycles
     * @param callable $callback
     */
    public function eachGiftCardChunk(?array $cycles, callable $callback): void
    {
        $this->giftCardService->eachGiftCardChunk($cycles, function (Collection $chunk) use ($callback) {
            $callback($chunk->map(function (object $giftCard) {
                return new AnalyticsGiftCardData(
                    $giftCard->number,
                    Money::RUB(intval($giftCard->nominal * 100)),
                    $giftCard->cycle,
                    Carbon::createFromFormat('Ymd', $giftCard->created_at),
                    $giftCard->sku
                );
            }));
        });
    }
}
