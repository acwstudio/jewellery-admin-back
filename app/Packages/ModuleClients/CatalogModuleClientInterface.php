<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Packages\DataObjects\Catalog\Brand\BrandData;
use App\Packages\DataObjects\Catalog\Brand\CreateBrandData;
use App\Packages\DataObjects\Catalog\Brand\UpdateBrandData;
use App\Packages\DataObjects\Catalog\Breadcrumb\BreadcrumbData;
use App\Packages\DataObjects\Catalog\Category\CategoryData;
use App\Packages\DataObjects\Catalog\Category\CategoryListItemData;
use App\Packages\DataObjects\Catalog\Category\CategoryListOptionsData;
use App\Packages\DataObjects\Catalog\Category\CategoryOptionsData;
use App\Packages\DataObjects\Catalog\Category\CreateCategoryData;
use App\Packages\DataObjects\Catalog\Category\Filter\CategoryFilterData;
use App\Packages\DataObjects\Catalog\Category\Slug\CategorySlugAliasData;
use App\Packages\DataObjects\Catalog\Category\Slug\CreateCategorySlugAliasData;
use App\Packages\DataObjects\Catalog\Category\UpdateCategoryData;
use App\Packages\DataObjects\Catalog\Feature\CreateFeatureData;
use App\Packages\DataObjects\Catalog\Feature\FeatureData;
use App\Packages\DataObjects\Catalog\Feature\FeatureListData;
use App\Packages\DataObjects\Catalog\Feature\GetListFeatureData;
use App\Packages\DataObjects\Catalog\Feature\UpdateFeatureData;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageData;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageGetListData;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageListData;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageUploadData;
use App\Packages\DataObjects\Catalog\Product\CreateProductData;
use App\Packages\DataObjects\Catalog\Product\ProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\Product\ProductItemExtendedData;
use App\Packages\DataObjects\Catalog\Product\ProductItemListData;
use App\Packages\DataObjects\Catalog\Product\ProductListAndFilterData;
use App\Packages\DataObjects\Catalog\Product\ProductListData;
use App\Packages\DataObjects\Catalog\Product\UpdateProductData;
use App\Packages\DataObjects\Catalog\ProductFeature\CreateProductFeatureData;
use App\Packages\DataObjects\Catalog\ProductFeature\ProductFeatureData;
use App\Packages\DataObjects\Catalog\ProductFeature\UpdateProductFeatureData;
use App\Packages\DataObjects\Catalog\ProductOffer\CreateProductOfferData;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\CreateProductOfferPriceData;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\ProductOfferPriceData;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\UpdateProductOfferPriceIsActiveData;
use App\Packages\DataObjects\Catalog\ProductOffer\ProductOfferData;
use App\Packages\DataObjects\Catalog\ProductOffer\Reservation\CreateProductOfferReservationData;
use App\Packages\DataObjects\Catalog\ProductOffer\Reservation\ProductOfferReservationData;
use App\Packages\DataObjects\Catalog\ProductOffer\Reservation\UpdateProductOfferReservationStatusData;
use App\Packages\DataObjects\Catalog\ProductOffer\Stock\CreateProductOfferStockData;
use App\Packages\DataObjects\Catalog\ProductOffer\Stock\ProductOfferStockData;
use App\Packages\DataObjects\Catalog\Seo\CreateSeoData;
use App\Packages\DataObjects\Catalog\Seo\Filter\SeoFilterData;
use App\Packages\DataObjects\Catalog\Seo\SeoData;
use App\Packages\DataObjects\Catalog\Seo\UpdateSeoData;
use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\Catalog\OfferStockReasonEnum;
use App\Packages\Exceptions\CircularRelationException;
use Illuminate\Support\Collection;

interface CatalogModuleClientInterface
{
    public function getCategory(int $id, CategoryOptionsData $options): ?CategoryData;

    public function getCategories(CategoryFilterData $filter, CategoryOptionsData $options): Collection;

    public function createCategory(CreateCategoryData $data): CategoryData;

    /**
     * @throws CircularRelationException
     */
    public function updateCategory(UpdateCategoryData $data): CategoryData;

    public function deleteCategory(int $id): void;

    /**
     * @param int $categoryId
     *
     * @return Collection<BreadcrumbData>
     */
    public function getBreadcrumbs(int $categoryId): Collection;

    public function getCategoryList(CategoryListOptionsData $options): Collection;

    public function getCategoryListItem(int $id, CategoryListOptionsData $options): CategoryListItemData;

    public function getProduct(int $id): ?ProductData;

    public function getProductBySlug(string $slug): ?ProductData;

    public function getProductItemExtended(int $id): ProductItemExtendedData;

    public function getProducts(ProductGetListData $data): ProductListData;

    public function getScoutProducts(ProductGetListData $data): ProductItemListData;

    public function getSeoProducts(ProductGetListData $data): ProductListAndFilterData;

    /**
     * @param array $ids
     * @return \Illuminate\Support\Collection<ProductData>
     */
    public function getProductDataCollection(array $ids): Collection;

    /**
     * @param array $skuList
     * @return \Illuminate\Support\Collection<ProductData>
     */
    public function getProductDataCollectionBySkuList(array $skuList): Collection;

    public function createProduct(CreateProductData $data): ProductData;

    public function updateProduct(UpdateProductData $data): ProductData;

    public function updateProductIsActive(array $ids, bool $isActive): void;

    public function deleteProduct(int $id): void;
    public function getPreviewImageList(PreviewImageGetListData $data): PreviewImageListData;

    public function createPreviewImage(PreviewImageUploadData $data): PreviewImageData;

    public function deletePreviewImage(int $id): void;

    public function createCategorySlugAlias(
        int $categoryId,
        CreateCategorySlugAliasData $createAliasData
    ): CategorySlugAliasData;

    public function getCategoryBySlug(string $slug, CategoryOptionsData $options): ?CategoryData;

    public function getCategoryListItemBySlug(string $slug, CategoryListOptionsData $options): CategoryListItemData;

    public function getAllBrands(): Collection;

    public function getBrandById(int $brandId): BrandData;

    public function createBrand(CreateBrandData $brandData): BrandData;

    public function updateBrand(int $id, UpdateBrandData $brandData): BrandData;
    public function deleteBrandById(int $id): SuccessData;

    public function getProductOffer(int $id): ProductOfferData;

    public function createProductOffer(CreateProductOfferData $data): ProductOfferData;

    public function deleteProductOffer(int $id): void;

    public function createProductOfferPrice(CreateProductOfferPriceData $data): ProductOfferPriceData;

    public function updateProductOfferPriceIsActive(UpdateProductOfferPriceIsActiveData $data): ProductOfferPriceData;

    public function getBreadcrumbsBySlug(string $slug): Collection;

    public function createProductOfferStock(
        CreateProductOfferStockData $data,
        ?OfferStockReasonEnum $reason = null
    ): ProductOfferStockData;

    public function getProductOfferStockAvailable(int $productOfferId): int;

    public function getProductOfferStockCurrent(int $productOfferId): int;

    public function createProductOfferReservation(
        CreateProductOfferReservationData $data
    ): ProductOfferReservationData;

    public function updateProductOfferReservationStatus(
        UpdateProductOfferReservationStatusData $data
    ): ProductOfferReservationData;

    public function importCategories(?callable $onEach = null): void;

    public function importProducts(?callable $onEach = null): void;

    public function importProductOfferPrices(OfferPriceTypeEnum $type, ?callable $onEach = null): void;

    public function importProductOfferStocks(?callable $onEach = null): void;

    public function importProductFilters(?callable $onEach = null): void;

    public function importProductLive(?callable $onEach = null): void;

    public function importProductSaleFromPromotion(int $promotionId): void;

    public function checkProductOfferPrices(): void;

    public function getFeature(int $id): FeatureData;

    public function getFeatures(GetListFeatureData $data): FeatureListData;

    public function createFeature(CreateFeatureData $data): FeatureData;

    public function updateFeature(UpdateFeatureData $data): FeatureData;

    public function deleteFeature(int $id): void;

    public function createProductFeature(CreateProductFeatureData $data): ProductFeatureData;

    public function updateProductFeature(UpdateProductFeatureData $data): ProductFeatureData;

    public function deleteProductFeature(string $uuid): void;

    public function addProductFeatureCollection(int $collectionId): void;

    public function getSeo(int $id): SeoData;

    public function getSeoCollection(SeoFilterData $data): Collection;

    public function createSeo(CreateSeoData $data): SeoData;

    public function updateSeo(UpdateSeoData $data): SeoData;

    public function deleteSeo(int $id): void;

    public function generateProductSlugs(?callable $onEach = null): void;
}
