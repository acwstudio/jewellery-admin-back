<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Packages\DataObjects\Catalog\Product\ProductData as CatalogProductData;
use App\Packages\ApiClients\Mindbox\Contracts\MindboxApiClientContract;
use App\Packages\ApiClients\Mindbox\Requests\Common\ProductData;
use App\Packages\ApiClients\Mindbox\Requests\Common\ProductIdsData;
use App\Packages\ApiClients\Mindbox\Requests\Common\ProductListItemData;
use App\Packages\ApiClients\Mindbox\Requests\WebsiteSetWithList\CreateWebsiteSetWithListData;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\ProductOfferPriceData;
use App\Packages\DataObjects\Catalog\ProductOffer\ProductOfferData;
use App\Packages\Events\WishlistProductChanged;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Str;
use Spatie\LaravelData\DataCollection;

class SetWishListWebsiteListener
{
    public function __construct(
        private readonly MindboxApiClientContract $mindboxApiClient,
        private readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    public function handle(WishlistProductChanged $event): void
    {
        $ids = $event->products->pluck('product_id')->toArray();
        $products = $this->catalogModuleClient->getProducts(ProductGetListData::from([
            'filter' => FilterProductData::from(['ids' => $ids])
        ]));
        $websiteSetWithListData = $this->getCreateWebsiteSetWithListData($products->items);

        $this->mindboxApiClient->websiteSetWithList($websiteSetWithListData);
    }

    private function getProductListItemData(CatalogProductData $product): ProductListItemData
    {
        /** @var ProductOfferData $productOffer */
        $productOffer = $product->trade_offers->first();
        /** @var ProductOfferPriceData $productOfferPrice */
        $productOfferPrice = $productOffer->prices->first();
        $offerId = $product->sku;

        $productListItemData = ProductListItemData::from([
            'productGroup' => new ProductData(
                new ProductIdsData(
                    website: $offerId
                )
            ),
            'count' => 1,
            'pricePerItem' => $this->getPriceAmount($productOfferPrice)
        ]);

        return $productListItemData;
    }

    private function getCreateWebsiteSetWithListData(DataCollection $products): CreateWebsiteSetWithListData
    {
        return new CreateWebsiteSetWithListData(
            $products->map(fn(CatalogProductData $product) => $this->getProductListItemData($product))->all()
        );
    }

    private function getPriceAmount(?ProductOfferPriceData $productOfferPrice): ?string
    {
        return $productOfferPrice?->transform()['price']->getAmount();
    }
}
