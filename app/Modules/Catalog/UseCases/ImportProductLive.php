<?php

declare(strict_types=1);

namespace App\Modules\Catalog\UseCases;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Catalog\Services\Import\ProductLiveImportService;
use App\Modules\Catalog\Services\ProductOfferPriceService;
use App\Modules\Catalog\Services\ProductService;
use App\Modules\Catalog\Support\Blueprints\ProductOfferPriceBlueprint;
use App\Packages\DataObjects\Catalog\Product\Import\ProductLive\ImportProductLiveData;
use App\Packages\DataObjects\Catalog\Product\Import\ProductLive\ProductLiveData;
use App\Packages\DataObjects\Catalog\Product\Import\ProductLive\ProductLivePriceData;
use App\Packages\DataObjects\Live\LiveProduct\CreateLiveProductData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\ModuleClients\LiveModuleClientInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Money\Money;
use Psr\Log\LoggerInterface;

class ImportProductLive
{
    public function __construct(
        private readonly LiveModuleClientInterface $liveModuleClient,
        private readonly ProductLiveImportService $productLiveImportService,
        private readonly ProductService $productService,
        private readonly ProductOfferPriceService $productOfferPriceService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(?callable $onEach = null): void
    {
        $this->productLiveImportService->import(function (ImportProductLiveData $data) use ($onEach) {
            try {
                $this->loop($data->products, $onEach);
            } catch (\Throwable $e) {
                $this->logger->error(
                    "Product Live import error",
                    ['exception' => $e]
                );
            }
        });
    }

    private function loop(Collection $productLiveDataCollection, ?callable $onEach = null): void
    {
        $this->liveModuleClient->unsetOnLiveProducts();
        if ($productLiveDataCollection->isEmpty()) {
            return;
        }

        $products = $this->productService->getProductBySkuList(
            $productLiveDataCollection->pluck('sku')->toArray()
        );

        if ($products->isEmpty()) {
            $this->logger->info('ImportProductLive::loop Get products empty');
            return;
        }

        $this->updateIsActiveProducts($products);

        /** @var ProductLiveData $productLiveData */
        foreach ($productLiveDataCollection as $productLiveData) {
            try {
                DB::transaction(function () use ($productLiveData, $products) {
                    $this->upsert($productLiveData, $products);
                });
            } catch (\Throwable $e) {
                $this->logger->error(
                    "[ImportProductLive] Error",
                    [
                        'product_sku' => $productLiveData->sku,
                        'message' => $e->getMessage()
                    ]
                );
            }

            if (null !== $onEach) {
                call_user_func($onEach);
            }
        }
    }

    private function updateIsActiveProducts(Collection $products): void
    {
        $productIds = $products->pluck('id')->toArray();
        $this->logger->info('ImportProductLive::updateIsActiveProducts', ['productIds' => $productIds]);
        $this->productService->updateProductIsActive($productIds, true);
    }

    private function upsert(ProductLiveData $data, Collection $products): void
    {
        $this->logger->info('ImportProductLive::upsert', ['product_sku' => $data->sku]);

        $product = $products->where('sku', '=', $data->sku)->first();
        if (!$product instanceof Product) {
            throw new \Exception('Product not found');
        }

        /** @var ProductLivePriceData $priceData */
        foreach ($data->prices as $priceData) {
            $this->updateProductOffer($product, $priceData->size, $priceData->price);
        }

        $this->updateOrCreateProductLive($product->id, $data->number, $data->datetime);
        $product->updateInScout();
    }

    private function updateProductOffer(Product $product, ?string $size, Money $money): void
    {
        if (empty($size)) {
            $productOffers = $product->productOffers()->getQuery()->get();
            /** @var ProductOffer $productOffer */
            foreach ($productOffers as $productOffer) {
                $this->createProductOfferPriceLive($productOffer, $money);
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

        $this->createProductOfferPriceLive($productOffer, $money);
    }

    private function createProductOfferPriceLive(ProductOffer $productOffer, Money $money): void
    {
        $this->logger->info(
            'ImportProductLive::createProductOfferPriceLive',
            ['product_offer_id' => $productOffer->getKey(), 'money' => $money->getAmount()]
        );

        $productOfferPrice = $productOffer->productOfferPrices()->getQuery()
            ->where('price', '=', $money->getAmount())
            ->where('type', '=', OfferPriceTypeEnum::LIVE->value)
            ->where('is_active', '=', true)
            ->first();

        if ($productOfferPrice instanceof ProductOfferPrice) {
            return;
        }

        $this->productOfferPriceService->createProductOfferPrice(
            new ProductOfferPriceBlueprint($money, OfferPriceTypeEnum::LIVE),
            $productOffer
        );
    }

    private function updateOrCreateProductLive(int $productId, int $number, Carbon $datetime): void
    {
        $this->liveModuleClient->createLiveProduct(
            new CreateLiveProductData(
                product_id: $productId,
                number: $number,
                started_at: $datetime,
                on_live: true
            )
        );
    }
}
