<?php

declare(strict_types=1);

namespace App\Modules\Catalog\UseCases;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Catalog\Services\ProductOfferPriceService;
use App\Modules\Catalog\Support\Blueprints\ProductOfferPriceBlueprint;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Illuminate\Support\Facades\App;
use Money\Money;
use Psr\Log\LoggerInterface;

abstract class AbstractImportProductOfferPrice
{
    private ProductOfferPriceService $productOfferPriceService;

    public function __construct(
        private readonly LoggerInterface $logger
    ) {
        $this->productOfferPriceService = App::make(ProductOfferPriceService::class);
    }

    abstract public function __invoke(?callable $onEach = null): void;

    protected function upsertProductOfferPrice(
        Product $product,
        Money $money,
        OfferPriceTypeEnum $priceType,
        ?string $size = null
    ): void {
        if (empty($size)) {
            $productOffers = $product->productOffers()->getQuery()->get();
            /** @var ProductOffer $productOffer */
            foreach ($productOffers as $productOffer) {
                $this->createProductOfferPrice($productOffer, $money, $priceType);
            }
            return;
        }

        $productOffer = $product->productOffers()->getQuery()
            ->where('size', '=', $size)
            ->get()
            ->first();

        if (!$productOffer instanceof ProductOffer) {
            return;
        }

        $this->createProductOfferPrice($productOffer, $money, $priceType);
    }

    private function createProductOfferPrice(
        ProductOffer $productOffer,
        Money $money,
        OfferPriceTypeEnum $priceType
    ): void {
        $this->logger->info(
            get_called_class() . '::createProductOfferPrice',
            [
                'product_offer_id' => $productOffer->getKey(),
                'money' => $money->getAmount(),
                'type' => $priceType->value
            ]
        );

        $productOfferPrice = $productOffer->productOfferPrices()->getQuery()
            ->where('price', '=', $money->getAmount())
            ->where('type', '=', $priceType->value)
            ->where('is_active', '=', true)
            ->first();

        if ($productOfferPrice instanceof ProductOfferPrice) {
            return;
        }

        if ($money->getAmount() == 0) {
            $productOffer->productOfferPrices()->getQuery()
                ->where('type', '=', $priceType)
                ->where('is_active', '=', true)
                ->update(['is_active' => false]);
            return;
        }

        $this->productOfferPriceService->createProductOfferPrice(
            new ProductOfferPriceBlueprint($money, $priceType),
            $productOffer
        );
    }
}
