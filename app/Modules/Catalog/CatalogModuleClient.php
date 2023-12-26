<?php

declare(strict_types=1);

namespace App\Modules\Catalog;

use App\Modules\Catalog\Helpers\CatalogHelper;
use App\Modules\Catalog\Models\Brand;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\CategoryListItem;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOfferStock;
use App\Modules\Catalog\Models\Seo;
use App\Modules\Catalog\Services\BrandService;
use App\Modules\Catalog\Services\BreadcrumbService;
use App\Modules\Catalog\Services\CategoryListService;
use App\Modules\Catalog\Services\CategoryService;
use App\Modules\Catalog\Services\CategorySlugAliasService;
use App\Modules\Catalog\Services\FeatureService;
use App\Modules\Catalog\Services\PreviewImageService;
use App\Modules\Catalog\Services\ProductFeatureService;
use App\Modules\Catalog\Services\ProductOfferPriceService;
use App\Modules\Catalog\Services\ProductOfferReservationService;
use App\Modules\Catalog\Services\ProductOfferService;
use App\Modules\Catalog\Services\ProductOfferStockService;
use App\Modules\Catalog\Services\ProductScoutService;
use App\Modules\Catalog\Services\ProductService;
use App\Modules\Catalog\Services\SeoService;
use App\Modules\Catalog\Support\Blueprints\CategoryBlueprint;
use App\Modules\Catalog\Support\Blueprints\FeatureBlueprint;
use App\Modules\Catalog\Support\Blueprints\ProductBlueprint;
use App\Modules\Catalog\Support\Blueprints\ProductFeatureBlueprint;
use App\Modules\Catalog\Support\Blueprints\ProductOfferBlueprint;
use App\Modules\Catalog\Support\Blueprints\ProductOfferPriceBlueprint;
use App\Modules\Catalog\Support\Blueprints\ProductOfferReservationBlueprint;
use App\Modules\Catalog\Support\Blueprints\ProductOfferStockBlueprint;
use App\Modules\Catalog\Support\Filters\CategoryFilter;
use App\Modules\Catalog\Support\Filters\SeoFilter;
use App\Modules\Catalog\Support\Pagination;
use App\Modules\Catalog\UseCases\CheckProductOfferPrices;
use App\Modules\Catalog\UseCases\GenerateProductSlugs;
use App\Modules\Catalog\UseCases\GetLiveProductIds;
use App\Modules\Catalog\UseCases\GetWishlistProductIds;
use App\Modules\Catalog\UseCases\ImportCategories;
use App\Modules\Catalog\UseCases\ImportProductLive;
use App\Modules\Catalog\UseCases\ImportProductOfferPriceLive;
use App\Modules\Catalog\UseCases\ImportProductFilter;
use App\Modules\Catalog\UseCases\ImportProductOfferPriceRegular;
use App\Modules\Catalog\UseCases\ImportProductOfferStocks;
use App\Modules\Catalog\UseCases\ImportProducts;
use App\Modules\Catalog\UseCases\ImportProductSaleFromPromotion;
use App\Modules\Catalog\UseCases\ProductFeatureCollection;
use App\Modules\Catalog\UseCases\StaticFilterProducts;
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
use App\Packages\Enums\Catalog\OfferReservationStatusEnum;
use App\Packages\Enums\Catalog\OfferStockReasonEnum;
use App\Packages\Exceptions\CircularRelationException;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

final class CatalogModuleClient implements CatalogModuleClientInterface
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly BreadcrumbService $breadcrumbService,
        private readonly CategoryListService $categoryListService,
        private readonly ProductService $productService,
        private readonly ProductScoutService $productScoutService,
        private readonly PreviewImageService $previewImageService,
        private readonly CategorySlugAliasService $categorySlugAliasService,
        private readonly BrandService $brandService,
        private readonly ProductOfferService $productOfferService,
        private readonly ProductOfferPriceService $productOfferPriceService,
        private readonly ProductOfferStockService $productOfferStockService,
        private readonly ProductOfferReservationService $productOfferReservationService,
        private readonly FeatureService $featureService,
        private readonly ProductFeatureService $productFeatureService,
        private readonly SeoService $seoService,
        private readonly StaticFilterProducts $staticFilterProducts,
    ) {
    }

    public function getCategory(int $id, CategoryOptionsData $options): ?CategoryData
    {
        $category = $this->categoryService->getCategory($id);

        if (is_null($category)) {
            return null;
        }
        $with = CatalogHelper::formatOptionsWith($options);

        return CategoryData::fromModel($category, $with);
    }

    public function getCategories(CategoryFilterData $filter, CategoryOptionsData $options): Collection
    {
        $categories = $this->categoryService->getCategories(
            new CategoryFilter(
                $filter->id,
                $filter->external_id
            )
        );

        $with = CatalogHelper::formatOptionsWith($options);

        return $categories->map(function (Category $category) use ($with) {
            return CategoryData::fromModel($category, $with);
        });
    }

    public function createCategory(CreateCategoryData $data): CategoryData
    {
        $categoryBlueprint = new CategoryBlueprint(
            $data->title,
            $data->h1,
            $data->description,
            $data->meta_title,
            $data->meta_description,
            $data->meta_keywords,
            $data->slug
        );

        $category = $this->categoryService->createCategory(
            $categoryBlueprint,
            $data->parent_id,
            $data->preview_image_id
        );

        return CategoryData::fromModel($category);
    }

    /**
     * @throws CircularRelationException
     */
    public function updateCategory(UpdateCategoryData $data): CategoryData
    {
        $categoryBlueprint = new CategoryBlueprint(
            $data->title,
            $data->h1,
            $data->description,
            $data->meta_title,
            $data->meta_description,
            $data->meta_keywords,
            $data->slug
        );

        $category = $this->categoryService->updateCategory(
            $data->id,
            $categoryBlueprint,
            $data->parent_id,
            $data->preview_image_id
        );

        return CategoryData::fromModel($category);
    }

    public function deleteCategory(int $id): void
    {
        $this->categoryService->deleteCategory($id);
    }

    /**
     * @param int $categoryId
     *
     * @return Collection<BreadcrumbData>
     */
    public function getBreadcrumbs(int $categoryId): Collection
    {
        return $this->breadcrumbService->getCategoryBreadcrumbs($categoryId)->map(function (Category $category) {
            return BreadcrumbData::fromCategory($category);
        });
    }

    public function getCategoryList(CategoryListOptionsData $options): Collection
    {
        $with = CatalogHelper::formatOptionsWithCategoryList($options);

        return $this->categoryListService->getCategoryList()->map(function (CategoryListItem $item) use ($with) {
            return CategoryListItemData::fromModel($item, $with);
        });
    }

    public function getCategoryListItem(int $id, CategoryListOptionsData $options): CategoryListItemData
    {
        $with = CatalogHelper::formatOptionsWithCategoryList($options);

        return CategoryListItemData::fromModel(
            $this->categoryListService->getCategoryListItem($id),
            $with
        );
    }

    public function getProduct(int $id): ?ProductData
    {
        $product = $this->productService->getProduct($id);

        if (is_null($product)) {
            return null;
        }

        /** @var array $wishlistIds */
        $wishlistIds = App::call(GetWishlistProductIds::class);

        /** @var array $wishlistIds */
        $liveIds = App::call(GetLiveProductIds::class, ['products' => new Collection([$product])]);

        return ProductData::fromModel(
            product: $product,
            isFullData: true,
            wishlist: $wishlistIds,
            liveIds: $liveIds
        );
    }

    public function getProductBySlug(string $slug): ?ProductData
    {
        $product = $this->productService->getProductBySlug($slug);

        if (is_null($product)) {
            return null;
        }

        /** @var array $wishlistIds */
        $wishlistIds = App::call(GetWishlistProductIds::class);

        /** @var array $wishlistIds */
        $liveIds = App::call(GetLiveProductIds::class, ['products' => new Collection([$product])]);

        return ProductData::fromModel(
            product: $product,
            isFullData: true,
            wishlist: $wishlistIds,
            liveIds: $liveIds
        );
    }

    public function getProductItemExtended(int $id): ProductItemExtendedData
    {
        /** @var array $wishlistIds */
        $wishlistIds = App::call(GetWishlistProductIds::class);

        /** TODO Для тестов. Не удается подключить collection для тестирования */
        if (app()->runningUnitTests()) {
            $product = $this->productService->getProduct($id);
            return ProductItemExtendedData::fromModel($product);
        }

        $product = $this->productScoutService->getProduct($id);

        return ProductItemExtendedData::customFromArray(
            product: $product,
            wishlist: $wishlistIds
        );
    }

    public function getProducts(ProductGetListData $data): ProductListData
    {
        $paginator = $this->productService->getProducts(
            new Pagination(
                $data->pagination?->page,
                $data->pagination?->per_page
            )
        );

        /** @var array $wishlistIds */
        $wishlistIds = App::call(GetWishlistProductIds::class);

        /** @var array $wishlistIds */
        $liveIds = App::call(GetLiveProductIds::class, ['products' => new Collection($paginator->items())]);

        return ProductListData::fromPaginator(
            paginator: $paginator,
            wishlist: $wishlistIds,
            liveIds: $liveIds,
            isFull: $data->is_full ?? false
        );
    }

    public function getScoutProducts(ProductGetListData $data): ProductItemListData
    {
        /** @var array $wishlistIds */
        $wishlistIds = App::call(GetWishlistProductIds::class);

        /** TODO Для тестов. Не удается подключить collection для тестирования */
        if (app()->runningUnitTests()) {
            $paginator = $this->productService->getProducts(
                new Pagination(
                    $data->pagination?->page,
                    $data->pagination?->per_page
                )
            );

            return ProductItemListData::fromModelPaginator(
                paginator: $paginator,
                wishlist: $wishlistIds
            );
        }

        $paginator = $this->productScoutService->getProducts($data);

        return ProductItemListData::fromPaginator(
            paginator: $paginator,
            wishlist: $wishlistIds
        );
    }

    public function getSeoProducts(ProductGetListData $data): ProductListAndFilterData
    {
        return $this->staticFilterProducts->getProducts($data);
    }

    /**
     * @param array $ids
     * @return \Illuminate\Support\Collection<ProductData>
     */
    public function getProductDataCollection(array $ids): Collection
    {
        $products = $this->productService->getProductByIds($ids);

        /** @var array $wishlistIds */
        $wishlistIds = App::call(GetWishlistProductIds::class);

        /** @var array $wishlistIds */
        $liveIds = App::call(GetLiveProductIds::class, ['products' => $products]);

        return $products->map(
            fn (Product $product) => ProductData::fromModel(
                product: $product,
                wishlist: $wishlistIds,
                liveIds: $liveIds
            )
        );
    }

    /**
     * @param array $skuList
     * @return \Illuminate\Support\Collection<ProductData>
     */
    public function getProductDataCollectionBySkuList(array $skuList): Collection
    {
        $products = $this->productService->getProductBySkuList($skuList);

        /** @var array $wishlistIds */
        $wishlistIds = App::call(GetWishlistProductIds::class);

        /** @var array $wishlistIds */
        $liveIds = App::call(GetLiveProductIds::class, ['products' => $products]);

        return $products->map(
            fn (Product $product) => ProductData::fromModel(
                product: $product,
                wishlist: $wishlistIds,
                liveIds: $liveIds
            )
        );
    }

    public function createProduct(CreateProductData $data): ProductData
    {
        $productBlueprint = new ProductBlueprint(
            $data->sku,
            $data->name,
            $data->summary,
            $data->description,
            $data->manufacture_country,
            $data->rank,
            $data->catalog_number,
            $data->supplier,
            $data->liquidity,
            $data->stamp,
            $data->meta_title,
            $data->meta_description,
            $data->meta_keywords,
            $data->is_active,
            $data->is_drop_shipping,
            $data->popularity
        );

        $product = $this->productService->createProduct(
            $productBlueprint,
            $data->categories,
            $data->preview_image_id,
            $data->brand_id,
            $data->images
        );

        return ProductData::fromModel($product);
    }

    public function updateProduct(UpdateProductData $data): ProductData
    {
        $productBlueprint = new ProductBlueprint(
            $data->sku,
            $data->name,
            $data->summary,
            $data->description,
            $data->manufacture_country,
            $data->rank,
            $data->catalog_number,
            $data->supplier,
            $data->liquidity,
            $data->stamp,
            $data->meta_title,
            $data->meta_description,
            $data->meta_keywords,
            $data->is_active,
            $data->is_drop_shipping,
            $data->popularity
        );

        $product = $this->productService->updateProduct(
            $data->id,
            $productBlueprint,
            $data->categories,
            $data->preview_image_id,
            $data->brand_id,
            $data->images
        );

        return ProductData::fromModel($product);
    }

    public function updateProductIsActive(array $ids, bool $isActive): void
    {
        $this->productService->updateProductIsActive($ids, $isActive);
    }

    public function deleteProduct(int $id): void
    {
        $this->productService->deleteProduct($id);
    }

    public function getPreviewImageList(PreviewImageGetListData $data): PreviewImageListData
    {
        $paginator = $this->previewImageService->getProductImageList(
            new Pagination(
                $data->pagination?->page,
                $data->pagination?->per_page
            )
        );

        return PreviewImageListData::fromPaginator($paginator);
    }

    public function createPreviewImage(PreviewImageUploadData $data): PreviewImageData
    {
        $previewImage = $this->previewImageService->createPreviewImage(
            $data->image
        );

        return PreviewImageData::fromModel($previewImage);
    }

    public function deletePreviewImage(int $id): void
    {
        $this->previewImageService->deletePreviewImage($id);
    }

    public function createCategorySlugAlias(
        int $categoryId,
        CreateCategorySlugAliasData $createAliasData
    ): CategorySlugAliasData {
        $category = $this->categoryService->getCategory($categoryId);

        $categorySlugAlias = $this->categorySlugAliasService->createCategorySlugAlias(
            $category,
            $createAliasData->slug
        );
        return CategorySlugAliasData::fromModel($categorySlugAlias);
    }

    public function getCategoryBySlug(string $slug, CategoryOptionsData $options): ?CategoryData
    {
        $category = $this->categoryService->getCategoryBySlug($slug);

        $with = CatalogHelper::formatOptionsWith($options);

        return CategoryData::fromModel($category, $with);
    }

    public function getCategoryListItemBySlug(string $slug, CategoryListOptionsData $options): CategoryListItemData
    {
        $with = CatalogHelper::formatOptionsWithCategoryList($options);
        return CategoryListItemData::fromModel(
            $this->categoryListService->getCategoryListItemBySlug($slug),
            $with
        );
    }

    public function getAllBrands(): Collection
    {
        return $this->brandService->getAll()->map(
            /** @phpstan-ignore-next-line */
            fn (Brand $brand) => BrandData::fromModel($brand)
        );
    }

    public function getBrandById(int $brandId): BrandData
    {
        return BrandData::fromModel($this->brandService->getById($brandId, true));
    }

    public function createBrand(CreateBrandData $brandData): BrandData
    {
        return BrandData::fromModel($this->brandService->create($brandData->name));
    }

    public function updateBrand(int $id, UpdateBrandData $brandData): BrandData
    {
        return BrandData::fromModel($this->brandService->update($id, $brandData->name));
    }

    public function deleteBrandById(int $id): SuccessData
    {
        $this->brandService->delete($id);
        return new SuccessData();
    }

    public function getProductOffer(int $id): ProductOfferData
    {
        $productOffer = $this->productOfferService->getProductOffer($id);

        return ProductOfferData::fromModel($productOffer);
    }

    public function createProductOffer(CreateProductOfferData $data): ProductOfferData
    {
        $productOfferBlueprint = new ProductOfferBlueprint(
            $data->size,
            $data->weight
        );
        $productOffer = $this->productOfferService->createProductOffer($productOfferBlueprint, $data->product_id);

        return ProductOfferData::fromModel($productOffer);
    }

    public function deleteProductOffer(int $id): void
    {
        $this->productOfferService->deleteProductOffer($id);
    }

    public function createProductOfferPrice(CreateProductOfferPriceData $data): ProductOfferPriceData
    {
        $productOfferPriceBlueprint = new ProductOfferPriceBlueprint(
            $data->price,
            $data->type
        );

        $productOfferPrice = $this->productOfferPriceService->createProductOfferPrice(
            $productOfferPriceBlueprint,
            $data->product_offer_id
        );

        return ProductOfferPriceData::fromModel($productOfferPrice);
    }

    public function updateProductOfferPriceIsActive(UpdateProductOfferPriceIsActiveData $data): ProductOfferPriceData
    {
        $productOfferPrice = $this->productOfferPriceService->updateProductOfferPriceIsActive(
            $data->type,
            $data->is_active,
            $data->product_offer_id
        );

        return ProductOfferPriceData::fromModel($productOfferPrice);
    }

    public function getBreadcrumbsBySlug(string $slug): Collection
    {
        return $this->breadcrumbService->getCategoryBreadcrumbsBySlug($slug)->map(function (Category $category) {
            return BreadcrumbData::fromCategory($category);
        });
    }

    public function createProductOfferStock(
        CreateProductOfferStockData $data,
        ?OfferStockReasonEnum $reason = null
    ): ProductOfferStockData {
        $productOfferStockBlueprint = new ProductOfferStockBlueprint(
            $data->count,
            $reason ?? OfferStockReasonEnum::MANUAL
        );

        $productOfferStock = $this->productOfferStockService->createProductOfferStock(
            $productOfferStockBlueprint,
            $data->product_offer_id
        );

        $productOfferStock->productOffer->product->updateInScout();

        return ProductOfferStockData::fromModel($productOfferStock);
    }

    public function getProductOfferStockAvailable(int $productOfferId): int
    {
        return $this->productOfferReservationService->getProductOfferStockAvailable($productOfferId);
    }

    public function getProductOfferStockCurrent(int $productOfferId): int
    {
        $productOfferStock = $this->productOfferStockService->getProductOfferStockCurrent($productOfferId);
        if ($productOfferStock instanceof ProductOfferStock) {
            return $productOfferStock->count;
        }

        return 0;
    }

    public function createProductOfferReservation(CreateProductOfferReservationData $data): ProductOfferReservationData
    {
        $productOfferReservationBlueprint = new ProductOfferReservationBlueprint(
            $data->count,
            OfferReservationStatusEnum::PENDING
        );

        $productOfferReservation = $this->productOfferReservationService->createProductOfferReservation(
            $productOfferReservationBlueprint,
            $data->product_offer_id
        );

        return ProductOfferReservationData::fromModel($productOfferReservation);
    }

    public function updateProductOfferReservationStatus(
        UpdateProductOfferReservationStatusData $data
    ): ProductOfferReservationData {
        $productOfferReservation = $this->productOfferReservationService->changeProductOfferReservationStatus(
            $data->reservation_id,
            $data->status
        );

        return ProductOfferReservationData::fromModel($productOfferReservation);
    }

    public function importCategories(?callable $onEach = null): void
    {
        App::call(ImportCategories::class, [$onEach]);
    }

    public function importProducts(?callable $onEach = null): void
    {
        App::call(ImportProducts::class, [$onEach]);
    }

    public function importProductOfferPrices(OfferPriceTypeEnum $type, ?callable $onEach = null): void
    {
        if (OfferPriceTypeEnum::LIVE === $type) {
            App::call(ImportProductOfferPriceLive::class, [$onEach]);
        } elseif (OfferPriceTypeEnum::REGULAR === $type) {
            App::call(ImportProductOfferPriceRegular::class, [$onEach]);
        } else {
            throw new \Exception('Non-existent import price. Type ' . $type->value);
        }
    }

    public function importProductOfferStocks(?callable $onEach = null): void
    {
        App::call(ImportProductOfferStocks::class, [$onEach]);
    }

    public function importProductFilters(?callable $onEach = null): void
    {
        App::call(ImportProductFilter::class, [$onEach]);
    }

    public function importProductLive(?callable $onEach = null): void
    {
        App::call(ImportProductLive::class, [$onEach]);
    }

    public function importProductSaleFromPromotion(int $promotionId): void
    {
        App::call(ImportProductSaleFromPromotion::class, ['promotionId' => $promotionId]);
    }

    public function checkProductOfferPrices(): void
    {
        App::call(CheckProductOfferPrices::class);
    }

    public function getFeature(int $id): FeatureData
    {
        $feature = $this->featureService->getFeature($id);
        return FeatureData::fromModel($feature);
    }

    public function getFeatures(GetListFeatureData $data): FeatureListData
    {
        $paginator = $this->featureService->getFeatures(
            new Pagination(
                $data->pagination?->page,
                $data->pagination?->per_page
            )
        );

        return FeatureListData::fromPaginator($paginator);
    }

    public function createFeature(CreateFeatureData $data): FeatureData
    {
        $feature = $this->featureService->createFeature(
            new FeatureBlueprint(
                $data->type,
                $data->value,
                $data->slug,
                $data->position
            )
        );

        return FeatureData::fromModel($feature);
    }

    public function updateFeature(UpdateFeatureData $data): FeatureData
    {
        $feature = $this->featureService->updateFeature(
            $data->id,
            new FeatureBlueprint(
                $data->type,
                $data->value,
                $data->slug,
                $data->position
            )
        );

        return FeatureData::fromModel($feature);
    }

    public function deleteFeature(int $id): void
    {
        $this->featureService->deleteFeature($id);
    }

    public function createProductFeature(CreateProductFeatureData $data): ProductFeatureData
    {
        $productFeatureBlueprint = new ProductFeatureBlueprint(
            $data->value,
            $data->is_main
        );

        $productFeature = $this->productFeatureService->createProductFeature(
            $productFeatureBlueprint,
            $data->product_id,
            $data->feature_id,
            $data->parent_product_feature_uuid
        );

        return ProductFeatureData::fromModel($productFeature);
    }

    public function updateProductFeature(UpdateProductFeatureData $data): ProductFeatureData
    {
        $productFeatureBlueprint = new ProductFeatureBlueprint(
            $data->value,
            $data->is_main
        );

        $productFeature = $this->productFeatureService->updateProductFeature(
            $data->product_feature_uuid,
            $productFeatureBlueprint,
            $data->parent_product_feature_uuid
        );

        return ProductFeatureData::fromModel($productFeature);
    }

    public function deleteProductFeature(string $uuid): void
    {
        $this->productFeatureService->deleteProductFeature($uuid);
    }

    public function addProductFeatureCollection(int $collectionId): void
    {
        App::call(ProductFeatureCollection::class, ['collectionId' => $collectionId]);
    }

    public function getSeo(int $id): SeoData
    {
        $seo = $this->seoService->getSeo($id);
        return SeoData::fromModel($seo);
    }

    public function getSeoCollection(SeoFilterData $data): Collection
    {
        $seoCollection = $this->seoService->getSeoCollection(
            new SeoFilter(id: $data->id, url: $data->url)
        );

        return $seoCollection->map(
            fn (Seo $seo) => SeoData::fromModel($seo)
        );
    }

    public function createSeo(CreateSeoData $data): SeoData
    {
        $seo = $this->seoService->createSeo(
            $data,
            $data->category_id,
            $data->parent_id
        );

        return SeoData::fromModel($seo);
    }

    public function updateSeo(UpdateSeoData $data): SeoData
    {
        $seo = $this->seoService->updateSeo(
            $data->id,
            $data,
            $data->category_id,
            $data->parent_id
        );

        return SeoData::fromModel($seo);
    }

    public function deleteSeo(int $id): void
    {
        $this->seoService->deleteSeo($id);
    }

    public function generateProductSlugs(?callable $onEach = null): void
    {
        App::call(GenerateProductSlugs::class, ['onEach' => $onEach]);
    }
}
