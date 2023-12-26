<?php

declare(strict_types=1);

namespace App\Modules\Catalog\UseCases;

use App\Modules\Catalog\Services\ProductOfferPriceService;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Live\Filter\FilterLiveProductData;
use App\Packages\DataObjects\Live\LiveProduct\GetLiveProductListData;
use App\Packages\DataObjects\Promotions\Sales\Filter\FilterSaleProductData;
use App\Packages\DataObjects\Promotions\Sales\SaleProduct\GetSaleProductListData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\ModuleClients\LiveModuleClientInterface;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

class CheckProductOfferPrices
{
    public function __construct(
        private readonly PromotionsModuleClientInterface $promotionsModuleClient,
        private readonly LiveModuleClientInterface $liveModuleClient,
        private readonly ProductOfferPriceService $productOfferPriceService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(): void
    {
        $this->logger->debug('[CheckProductOfferPrices] Started checking prices.');
        try {
            $this->checkLivePrices();
            $this->checkSalePrices();
            $this->logger->info('[CheckProductOfferPrices] Successful checked prices.');
        } catch (\Throwable $e) {
            $this->logger->error(
                "[CheckProductOfferPrices] Error checking prices.",
                ['exception' => $e]
            );
        }
    }

    private function checkLivePrices(): void
    {
        $notActiveLiveProducts = $this->getLiveProducts(false);
        if ($notActiveLiveProducts->isEmpty()) {
            return;
        }

        $productIds = $notActiveLiveProducts->pluck('product_id')->all();
        unset($notActiveLiveProducts);
        $this->logger->debug(
            "[CheckProductOfferPrices] Check Live Products.",
            ['ids' => $productIds]
        );
        $this->productOfferPriceService->updateProductOfferPriceIsActiveByProductIds(
            $productIds,
            OfferPriceTypeEnum::LIVE,
            false,
            true
        );
    }

    private function checkSalePrices(): void
    {
        $notActiveSaleProducts = $this->getSaleProducts(false);
        if ($notActiveSaleProducts->isEmpty()) {
            return;
        }

        $productIds = $notActiveSaleProducts->pluck('product_id')->all();
        unset($notActiveSaleProducts);
        $this->logger->debug(
            "[CheckProductOfferPrices] Check Sale Products.",
            ['ids' => $productIds]
        );
        $this->productOfferPriceService->updateProductOfferPriceIsActiveByProductIds(
            $productIds,
            OfferPriceTypeEnum::SALE,
            false,
            true
        );
    }

    private function getLiveProducts(bool $isActive): Collection
    {
        $responseCollection = new Collection();
        $isRepeat = true;
        $page = 1;

        while ($isRepeat) {
            $responseListData = $this->liveModuleClient->getShortLiveProducts(
                new GetLiveProductListData(
                    pagination: new PaginationData($page, 100),
                    filter: new FilterLiveProductData(is_active: $isActive)
                )
            );
            $responseCollection = $responseCollection->merge($responseListData->items->all());
            $isRepeat = $responseListData->pagination->last_page > $responseListData->pagination->page;
            $page++;
        }

        return $responseCollection;
    }

    private function getSaleProducts(bool $isActive): Collection
    {
        $responseCollection = new Collection();
        $isRepeat = true;
        $page = 1;

        while ($isRepeat) {
            $responseListData = $this->promotionsModuleClient->getSaleProducts(
                new GetSaleProductListData(
                    pagination: new PaginationData($page, 100),
                    filter: new FilterSaleProductData(is_active: $isActive)
                )
            );
            $responseCollection = $responseCollection->merge($responseListData->items->all());
            $isRepeat = $responseListData->pagination->last_page > $responseListData->pagination->page;
            $page++;
        }

        return $responseCollection;
    }
}
