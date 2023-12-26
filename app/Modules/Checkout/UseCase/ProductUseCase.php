<?php

declare(strict_types=1);

namespace App\Modules\Checkout\UseCase;

use App\Packages\ApiClients\Enterprise1C\Contracts\Enterprise1CApiClientContract;
use App\Packages\ApiClients\Enterprise1C\Request\ProductsGetCount\ProductData;
use App\Packages\ApiClients\Enterprise1C\Request\ProductsGetCount\ProductsGetStockRequestData;
use App\Packages\ApiClients\Enterprise1C\Response\ProductsGetCount\ProductStockData;
use App\Packages\DataObjects\Catalog\ProductOffer\Stock\CreateProductOfferStockData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\ShopCartItemData;
use App\Packages\Enums\Catalog\OfferStockReasonEnum;
use App\Packages\Exceptions\Checkout\InsufficientQtyOfGoodsInStockException;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

class ProductUseCase
{
    public function __construct(
        private readonly Enterprise1CApiClientContract $enterprise1CApiClient,
        private readonly CatalogModuleClientInterface $catalogModuleClient,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param Collection<ShopCartItemData> $items
     * @throws InsufficientQtyOfGoodsInStockException
     */
    public function checkStock(Collection $items): void
    {
        $products = $this->getProductsGetCount($items);

        $errorProducts = [];
        foreach ($items as $item) {
            $stockCount = $this->getProductStockCount($products, $item->sku, $item->size);
            if ($stockCount < $item->count) {
                $this->logger->error('ProductUseCase::checkStock', [
                    'stockCount1C' => $stockCount,
                    'productOfferId' => $item->product_offer_id,
                ]);
                $this->updateProductOfferStockCount($item->product_offer_id, $stockCount);
                $errorProducts[] = ['sku' => $item->sku, 'size' => $item->size, 'maxCount' => $stockCount];
            }
        }

        if (!empty($errorProducts)) {
            throw (new InsufficientQtyOfGoodsInStockException())->setErrorProducts($errorProducts);
        }
    }

    private function updateProductOfferStockCount(int $productOfferId, int $stockCount): void
    {
        $productOfferStockCount = $this->catalogModuleClient->getProductOfferStockCurrent($productOfferId);

        if ($productOfferStockCount !== $stockCount) {
            $this->catalogModuleClient->createProductOfferStock(
                new CreateProductOfferStockData($productOfferId, $stockCount),
                OfferStockReasonEnum::NEW
            );
        }
    }

    private function getProductsGetCount(Collection $items): Collection
    {
        $productsGetCountRequestData = $this->createProductsGetStockRequestData($items);
        $response = $this->enterprise1CApiClient->productsGetStock($productsGetCountRequestData);

        if (!$response->result) {
            $this->logger->error($response->errorMessage);
            return collect();
        }

        return collect($response->products->all());
    }

    private function createProductsGetStockRequestData(Collection $items): ProductsGetStockRequestData
    {
        $data = [];

        /** @var ShopCartItemData $item */
        foreach ($items as $item) {
            $data[] = new ProductData($item->sku, $item->size ?? '');
        }

        return new ProductsGetStockRequestData(
            ProductData::collection($data)
        );
    }

    private function getProductStockCount(Collection $products, string $sku, ?string $size = null): int
    {
        $productStockData = $products
            ->where('sku', '=', $sku)
            ->where('size', '=', $size ?? '')
            ->first();

        if (!$productStockData instanceof ProductStockData) {
            return 0;
        }

        return $productStockData->stockCount;
    }
}
