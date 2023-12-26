<?php

declare(strict_types=1);

namespace App\Modules\Catalog\UseCases;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Services\Import\ProductOfferPriceRegularImportService;
use App\Modules\Catalog\Services\ProductService;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\Import\ImportProductOfferPriceRegularData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Events\Sync\ProductOfferPricesImported;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;

class ImportProductOfferPriceRegular extends AbstractImportProductOfferPrice
{
    public function __construct(
        private readonly ProductOfferPriceRegularImportService $productOfferPriceRegularImportService,
        private readonly ProductService $productService,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct($logger);
    }

    public function __invoke(?callable $onEach = null): void
    {
        $this->productOfferPriceRegularImportService->import(
            function (ImportProductOfferPriceRegularData $data) use ($onEach) {
                try {
                    DB::transaction(function () use ($data) {
                        $this->upsert($data);
                    });
                } catch (\Throwable $e) {
                    $this->logger->error(
                        "[ImportProductOfferPriceRegular] Product offer prices with
                        extID: $data->external_id import error",
                        ['exception' => $e]
                    );
                }

                if (null !== $onEach) {
                    call_user_func($onEach);
                }

                ProductOfferPricesImported::dispatch($data->data);
            }
        );
    }

    private function upsert(ImportProductOfferPriceRegularData $data): void
    {
        $product = $this->productService->getProductByExternalId($data->external_id);

        if (!$product instanceof Product) {
            throw new \Exception('Product not found in database');
        }

        $this->upsertProductOfferPrice(
            $product,
            $data->regularMoney,
            OfferPriceTypeEnum::REGULAR,
            $data->size
        );
        $product->updateInScout();
    }
}
