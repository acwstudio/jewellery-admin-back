<?php

declare(strict_types=1);

namespace App\Modules\Catalog\UseCases;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Services\ProductOfferPriceService;
use App\Modules\Catalog\Services\ProductService;
use App\Modules\Catalog\Support\Blueprints\ProductOfferPriceBlueprint;
use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Enums\PromotionBenefitTypeFormEnum;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\Import\ImportProductOfferPriceSaleData;
use App\Packages\DataObjects\Promotions\Promotion\Benefit\Product\PromotionBenefitProductData;
use App\Packages\DataObjects\Promotions\Promotion\Benefit\PromotionBenefitData;
use App\Packages\DataObjects\Promotions\Promotion\PromotionData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Money\Money;
use Psr\Log\LoggerInterface;

class ImportProductSaleFromPromotion
{
    public function __construct(
        private readonly PromotionsModuleClientInterface $promotionsModuleClient,
        private readonly ProductService $productService,
        private readonly ProductOfferPriceService $productOfferPriceService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(int $promotionId): void
    {
        try {
            $promotion = $this->promotionsModuleClient->getPromotion($promotionId);
            $this->upsert($promotion);
        } catch (\Throwable $e) {
            $this->logger->error(
                "[ImportProductSaleFromPromotion] Product Sale import error.",
                ['exception' => $e]
            );
        }
    }

    private function upsert(PromotionData $data): void
    {
        $benefits = $this->getSaleBenefits($data);
        if ($benefits->isEmpty()) {
            throw new \Exception('Empty sale benefits.');
        }

        if (!$data->is_active) {
            $this->logger->error(
                '[ImportProductSaleFromPromotion] Promotion is not active.',
                ['promotion_id' => $data->id]
            );
            return;
        }

        /** @var PromotionBenefitData $benefit */
        foreach ($benefits as $benefit) {
            $importProducts = $this->getImportProducts($benefit);
            if ($importProducts->isEmpty()) {
                $this->logger->error('[ImportProductSaleFromPromotion] Empty import products.');
                continue;
            }

            $products = $this->getProductsBySku($importProducts);
            if ($products->isEmpty()) {
                $this->logger->error('[ImportProductSaleFromPromotion] Empty products by sku.');
                continue;
            }

            $this->loop($products, $importProducts);
        }
    }

    private function loop(Collection $products, Collection $importProducts): void
    {
        $errorProducts = [];

        /** @var ImportProductOfferPriceSaleData $importProduct */
        foreach ($importProducts as $importProduct) {
            try {
                $product = $products->where('sku', '=', $importProduct->sku)->first();
                if (null === $product) {
                    throw new \Exception('Empty product by sku.');
                }
                DB::transaction(function () use ($product, $importProduct) {
                    $this->updateProductOffer($product, $importProduct->size, $importProduct->money);
                });
            } catch (\Throwable $e) {
                $errorProducts[$e->getMessage()][] = $importProduct->sku;
            }
        }

        if (!empty($errorProducts)) {
            $this->logger->error(
                '[ImportProductSaleFromPromotion] No import sale products.',
                ['errors' => $errorProducts]
            );
        } else {
            $this->logger->info(
                "[ImportProductSaleFromPromotion] Product Sale import successful."
            );
        }
    }

    private function getSaleBenefits(PromotionData $data): Collection
    {
        $benefits = $data->benefits
            ->where('type', '=', PromotionBenefitTypeEnum::SALE);

        return collect($benefits->all());
    }

    private function getImportProducts(PromotionBenefitData $benefit): Collection
    {
        return match ($benefit->type_form) {
            PromotionBenefitTypeFormEnum::SALE_PRICE => $this->getBenefitProducts($benefit),
            default => collect()
        };
    }

    private function getProductsBySku(Collection $importProducts): Collection
    {
        return $this->productService->getProductBySkuList(
            $importProducts->pluck('sku')->toArray()
        );
    }

    private function getBenefitProducts(PromotionBenefitData $benefit): Collection
    {
        $products = collect($benefit->products->all());

        if ($products->isEmpty()) {
            return new Collection();
        }

        $importProducts = new Collection();
        /** @var PromotionBenefitProductData $product */
        foreach ($products as $product) {
            $data = new ImportProductOfferPriceSaleData(
                $product->external_id,
                $product->sku,
                $product->price,
                $product->size
            );
            $importProducts->add($data);
        }

        return $importProducts;
    }

    private function updateProductOffer(Product $product, ?string $size, Money $money): void
    {
        if (empty($size)) {
            $productOffers = $product->productOffers()->getQuery()->get();
            /** @var ProductOffer $productOffer */
            foreach ($productOffers as $productOffer) {
                $this->createProductOfferPrice($productOffer, $money);
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

        $this->createProductOfferPrice($productOffer, $money);
        $product->updateInScout();
    }

    private function createProductOfferPrice(ProductOffer $productOffer, Money $money): void
    {
        $this->productOfferPriceService->createProductOfferPrice(
            new ProductOfferPriceBlueprint($money, OfferPriceTypeEnum::SALE),
            $productOffer
        );
    }
}
