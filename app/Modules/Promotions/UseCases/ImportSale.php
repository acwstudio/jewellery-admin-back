<?php

declare(strict_types=1);

namespace App\Modules\Promotions\UseCases;

use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Enums\PromotionBenefitTypeFormEnum;
use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Models\PromotionBenefitProduct;
use App\Modules\Promotions\Modules\Sales\Models\Sale;
use App\Modules\Promotions\Modules\Sales\Services\SaleProductService;
use App\Modules\Promotions\Modules\Sales\Services\SaleService;
use App\Modules\Promotions\Modules\Sales\Support\Blueprints\SaleBlueprint;
use App\Modules\Promotions\Modules\Sales\Support\Blueprints\SaleProductBlueprint;
use App\Modules\Promotions\Services\PromotionService;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Promotions\Sales\SaleProduct\Import\ImportSaleProductData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;

class ImportSale
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient,
        private readonly PromotionService $promotionService,
        private readonly SaleService $saleService,
        private readonly SaleProductService $saleProductService,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(int $promotionId): void
    {
        try {
            $promotion = $this->promotionService->getById($promotionId);
            $benefits = $this->getSaleBenefits($promotion);
            $this->loop($promotion, $benefits);
        } catch (\Throwable $e) {
            $this->logger->emergency(
                '[ImportSale] Failed to import sale. Service shutdown.',
                ['exception' => $e]
            );
        }
    }

    private function getSaleBenefits(Promotion $promotion): Collection
    {
        $benefits = $promotion->benefits
            ->where('type', '=', PromotionBenefitTypeEnum::SALE);

        if ($benefits->isEmpty()) {
            throw new \Exception('Promotion benefits is empty');
        }

        return $benefits;
    }

    private function loop(Promotion $promotion, Collection $benefits): void
    {
        foreach ($benefits as $benefit) {
            try {
                $importProducts = $this->getImportSaleProducts($benefit);
                if ($importProducts->isEmpty()) {
                    $this->logger->error('[ImportSale] Empty import products.');
                    continue;
                }

                DB::transaction(function () use ($promotion, $importProducts) {
                    $this->upsert($promotion, $importProducts);
                });
            } catch (\Throwable $e) {
                $this->logger->emergency(
                    '[ImportSale] Failed to create sale.',
                    ['exception' => $e]
                );
            }
        }
    }

    private function upsert(Promotion $promotion, Collection $products): void
    {
        $sale = $this->getSale($promotion);
        /** @var ImportSaleProductData $product */
        foreach ($products->unique('product_id') as $product) {
            $this->saleProductService->createOrUpdate(
                $sale,
                new SaleProductBlueprint($product->product_id)
            );
        }
    }

    /**
     * @param PromotionBenefit $benefit
     * @return Collection<ImportSaleProductData>
     */
    private function getImportSaleProducts(PromotionBenefit $benefit): Collection
    {
        return match ($benefit->type_form) {
            PromotionBenefitTypeFormEnum::SALE_PRICE => $this->getBenefitProducts($benefit),
            default => new Collection()
        };
    }

    private function getBenefitProducts(PromotionBenefit $benefit): Collection
    {
        /** @var Collection<PromotionBenefitProduct> $saleProducts */
        $saleProducts = $benefit->products;
        if ($saleProducts->isEmpty()) {
            return new Collection();
        }

        $sku = implode(',', $saleProducts->pluck('sku')->all());
        $products = $this->getCatalogProducts(new FilterProductData(sku: $sku));

        $this->logger->info(
            '[ImportSale] Sale sku products',
            ['sku' => $saleProducts->pluck('sku')->all()]
        );

        $importSaleProducts = new Collection();
        /** @var \App\Packages\DataObjects\Catalog\Product\ProductData $product */
        foreach ($products as $product) {
            /** @var PromotionBenefitProduct|null $saleProduct */
            $saleProduct = $saleProducts->where('sku', '=', $product->sku)->first();
            if (null === $saleProduct) {
                $this->logger->error('[ImportSale] Extra product from the catalog', [
                    'product_id' => $product->id,
                    'product_sku' => $product->sku
                ]);
                continue;
            }

            $data = new ImportSaleProductData(
                $product->id,
                $product->sku,
                $saleProduct->price
            );
            $importSaleProducts->add($data);
        }

        return $importSaleProducts;
    }

    private function getSale(Promotion $promotion): Sale
    {
        $sale = $this->saleService->getSaleByPromotion($promotion);

        if (null === $sale) {
            $sale = $this->saleService->create(
                $promotion,
                new SaleBlueprint($promotion->description, Str::slug($promotion->description))
            );
        }

        return $sale;
    }

    private function getCatalogProducts(FilterProductData $filterProductData): Collection
    {
        $productCollection = new Collection();
        $isRepeat = true;
        $page = 1;

        while ($isRepeat) {
            $data = new ProductGetListData(
                pagination: new PaginationData($page, 100),
                filter: $filterProductData,
            );
            /** @var \App\Packages\DataObjects\Catalog\Product\ProductListData $productListData */
            $productListData = $this->catalogModuleClient->getProducts($data);
            $productCollection = $productCollection->merge($productListData->items->all());

            $isRepeat = $productListData->pagination->last_page > $productListData->pagination->page;
            $page++;
        }

        return $productCollection;
    }
}
