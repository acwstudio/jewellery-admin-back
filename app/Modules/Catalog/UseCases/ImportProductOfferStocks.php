<?php

declare(strict_types=1);

namespace App\Modules\Catalog\UseCases;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferStock;
use App\Modules\Catalog\Services\Import\ProductOfferStockImportService;
use App\Modules\Catalog\Services\ProductOfferStockService;
use App\Modules\Catalog\Services\ProductService;
use App\Modules\Catalog\Support\Blueprints\ProductOfferStockBlueprint;
use App\Packages\DataObjects\Catalog\ProductOffer\Stock\ImportProductOfferStockData;
use App\Packages\Enums\Catalog\OfferStockReasonEnum;
use App\Packages\Events\Sync\ProductOfferStocksImported;
use Psr\Log\LoggerInterface;

class ImportProductOfferStocks
{
    public function __construct(
        private readonly ProductOfferStockImportService $productOfferStockImportService,
        private readonly ProductService $productService,
        private readonly ProductOfferStockService $productOfferStockService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(?callable $onEach = null): void
    {
        $this->productOfferStockImportService->import(function (ImportProductOfferStockData $data) use ($onEach) {
            try {
                $this->upsert($data);
            } catch (\Throwable $e) {
                $this->logger->error(
                    "Product offer stocks with extID: $data->external_id import error",
                    ['exception' => $e]
                );
            }

            if (null !== $onEach) {
                call_user_func($onEach);
            }
        });

        ProductOfferStocksImported::dispatch();
    }

    private function upsert(ImportProductOfferStockData $data): void
    {
        $product = $this->productService->getProductByExternalId($data->external_id);

        if (!$product instanceof Product) {
            throw new \Exception('Product not found in database');
        }

        $this->upsertProductOfferStock($product, $data->size, $data->count);
        $product->updateInScout();
    }

    private function upsertProductOfferStock(Product $product, ?string $size, int $count): void
    {
        $productOffers = $product->productOffers()
            ->getQuery()
            ->where('size', '=', $size)
            ->get();

        /** @var ProductOffer $productOffer */
        foreach ($productOffers as $productOffer) {
            $this->createProductOfferStock($productOffer, $count);
        }
    }

    private function createProductOfferStock(ProductOffer $productOffer, int $count): void
    {
        $productOfferStock = $productOffer->productOfferStocks()->getQuery()
            ->where('count', '=', $count)
            ->where('reason', '=', OfferStockReasonEnum::NEW->value)
            ->where('is_current', '=', true)
            ->first();

        if ($productOfferStock instanceof ProductOfferStock) {
            return;
        }

        $this->productOfferStockService->createProductOfferStock(
            new ProductOfferStockBlueprint($count, OfferStockReasonEnum::NEW),
            $productOffer
        );
    }
}
