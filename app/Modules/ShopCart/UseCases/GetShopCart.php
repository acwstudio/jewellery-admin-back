<?php

declare(strict_types=1);

namespace App\Modules\ShopCart\UseCases;

use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Models\ShopCartItem;
use App\Modules\ShopCart\Services\ShopCartService;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\ProductOfferPriceData;
use App\Packages\DataObjects\Catalog\ProductOffer\ProductOfferData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Promotions\Promocode\Price\Filter\FilterPromocodePriceData;
use App\Packages\DataObjects\Promotions\Promocode\Price\GetPromocodePriceListData;
use App\Packages\DataObjects\Promotions\Promocode\Price\PromocodePriceData;
use App\Packages\DataObjects\ShopCart\ShopCartData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\ShopCartItemData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use Illuminate\Support\Collection;
use Spatie\LaravelData\DataCollection;

class GetShopCart
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient,
        private readonly PromotionsModuleClientInterface $promotionsModuleClient,
        private readonly ShopCartService $shopCartService,
    ) {
    }

    public function __invoke(?string $token): ShopCartData
    {
        $shopCart = $this->shopCartService->getOrCreateShopCart($token);
        return $this->createShopCartData($shopCart);
    }

    private function createShopCartData(ShopCart $shopCart): ShopCartData
    {
        return new ShopCartData(
            $shopCart->token,
            $this->createShopCartItemDataCollection($shopCart),
            $this->promotionsModuleClient->getActivePromocode($shopCart->token)
        );
    }

    private function createShopCartItemDataCollection(ShopCart $shopCart): DataCollection
    {
        $items = $shopCart->items;
        if ($items->isEmpty()) {
            return ShopCartItemData::collection([]);
        }

        $shopCartItems = [];
        $products = $this->getCatalogProducts($items);
        /** @var ShopCartItem $item */
        foreach ($items as $item) {
            /** @var ProductData|null $product */
            $product = $products->where('id', '=', $item->product_id)->first();
            if (null === $product) {
                continue;
            }

            /** @var ProductOfferData|null $offer */
            $offer = $product->trade_offers->where('id', '=', $item->product_offer_id)->first();
            if (null === $offer) {
                continue;
            }

            $shopCartItems[] = $this->createShopCartItemData($item, $product, $offer, $shopCart->token);
        }

        return ShopCartItemData::collection($shopCartItems);
    }

    private function createShopCartItemData(
        ShopCartItem $item,
        ProductData $productData,
        ProductOfferData $productOfferData,
        string $shopCartToken,
    ): ShopCartItemData {
        return new ShopCartItemData(
            $item->product_id,
            $item->product_offer_id,
            $productOfferData->size,
            $item->count,
            $productData->sku,
            $productData->name,
            $item->selected,
            $productData->preview_image,
            $this->createProductOfferPriceDataCollection($productOfferData, $shopCartToken),
            $productData->external_id ?? 'nullable',
            $productData->slug
        );
    }

    private function createProductOfferPriceDataCollection(
        ProductOfferData $productOfferData,
        string $shopCartToken
    ): DataCollection {
        $productOfferPriceCollection = collect($productOfferData->prices->all());

        $promocodePrice = $this->getPromotionPromocodePrice($productOfferData->id, $shopCartToken);

        if ($promocodePrice !== null) {
            $productOfferPriceCollection->push(
                ProductOfferPriceData::from([
                    'id' => PHP_INT_MAX,
                    'price' => $promocodePrice->price,
                    'type' => OfferPriceTypeEnum::PROMOCODE->value
                ])
            );
        }

        return ProductOfferPriceData::collection($productOfferPriceCollection);
    }

    private function getCatalogProducts(Collection $items): Collection
    {
        $ids = $items->unique('product_id')->pluck('product_id')->all();
        $response = $this->catalogModuleClient->getProducts(
            new ProductGetListData(
                pagination: new PaginationData(
                    page: 1,
                    per_page: count($ids)
                ),
                filter: new FilterProductData(
                    ids: $ids
                )
            )
        );

        return collect($response->items->all());
    }

    private function getPromotionPromocodePrice(int $productOfferId, ?string $shopCartToken): ?PromocodePriceData
    {
        $promocodePrices = $this->promotionsModuleClient->getPromocodePrices(
            new GetPromocodePriceListData(
                filter: new FilterPromocodePriceData(
                    $shopCartToken,
                    $productOfferId
                )
            )
        );

        if ($promocodePrices->isEmpty()) {
            return null;
        }

        /** @var PromocodePriceData|null $promocodePrice */
        $promocodePrice = $promocodePrices->first();
        return $promocodePrice;
    }
}
