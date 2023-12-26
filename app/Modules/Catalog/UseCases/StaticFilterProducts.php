<?php

declare(strict_types=1);

namespace App\Modules\Catalog\UseCases;

use App\Modules\Catalog\Models\Seo;
use App\Modules\Catalog\Services\ProductService;
use App\Modules\Catalog\Services\SeoService;
use App\Modules\Catalog\Support\Filters\SeoFilter;
use App\Modules\Catalog\Support\Pagination;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\Product\ProductListAndFilterData;
use App\Packages\Exceptions\Catalog\SeoException;
use Illuminate\Support\Facades\App;

class StaticFilterProducts
{
    public function __construct(
        private readonly SeoService $seoService,
        private readonly ProductService $productService,
    ) {
    }

    public function getProducts(ProductGetListData $data): ProductListAndFilterData
    {
        if (empty($data->seo_url)) {
            throw $this->createSeoException('Пустая SEO ссылка');
        }

        $seoCollection = $this->seoService->getSeoCollection(new SeoFilter(url: $data->seo_url));

        if ($seoCollection->isEmpty()) {
            throw $this->createSeoException('SEO не найдено');
        }

        /** @var Seo $seo */
        $seo = $seoCollection->first();
        $productGetListData = $this->getProductGetListData($data, $seo->filters);

        $paginator = $this->productService->getProducts(
            new Pagination($data->pagination?->page, $data->pagination?->per_page)
        );

        /** @var array $wishlistIds */
        $wishlistIds = App::call(GetWishlistProductIds::class);

        return ProductListAndFilterData::fromPaginatorAndFilter(
            $paginator,
            $wishlistIds,
            $productGetListData->filter
        );
    }

    private function getProductGetListData(ProductGetListData $data, array $filter): ProductGetListData
    {
        $filter = $this->preFilters($filter);

        return new ProductGetListData(
            sort_by: $data->sort_by,
            sort_order: $data->sort_order,
            filter: FilterProductData::from($filter)
        );
    }

    private function createSeoException(string $description): SeoException
    {
        $exception = new SeoException();
        $exception->setDescription($description);

        return $exception;
    }

    private function preFilters(array $filters): array
    {
        $preFilters = [
            'in_stock' => true,
            'has_image' => true,
            'is_active' => true
        ];

        foreach ($preFilters as $key => $value) {
            $filter = $filters[$key] ?? null;
            if (null === $filter) {
                $filters[$key] = $value;
            }
        }

        return $filters;
    }
}
