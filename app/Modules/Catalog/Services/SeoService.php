<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Seo;
use App\Modules\Catalog\Repositories\CategoryRepository;
use App\Modules\Catalog\Repositories\SeoRepository;
use App\Modules\Catalog\Support\Filters\SeoFilter;
use App\Packages\DataObjects\Catalog\Seo\CreateSeoData;
use App\Packages\DataObjects\Catalog\Seo\UpdateSeoData;
use Illuminate\Support\Collection;

class SeoService
{
    public function __construct(
        private readonly SeoRepository $seoRepository,
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    public function getSeo(int $id): ?Seo
    {
        return $this->seoRepository->getById($id);
    }

    public function getSeoCollection(SeoFilter $filter): Collection
    {
        return $this->seoRepository->getList($filter);
    }

    public function createSeo(
        CreateSeoData $createSeoData,
        Category|int $category,
        Seo|int|null $parent = null
    ): Seo {
        if (is_int($category)) {
            $category = $this->categoryRepository->getCategory($category, true);
        }

        if (is_int($parent)) {
            $parent = $this->seoRepository->getById($parent, true);
        }

        return $this->seoRepository->create(
            $createSeoData,
            $category,
            $parent
        );
    }

    public function updateSeo(
        Seo|int $seo,
        UpdateSeoData $updateSeoData,
        Category|int $category,
        Seo|int|null $parent = null
    ): Seo {
        if (is_int($seo)) {
            $seo = $this->seoRepository->getById($seo, true);
        }

        if (is_int($category)) {
            $category = $this->categoryRepository->getCategory($category, true);
        }

        if (is_int($parent)) {
            $parent = $this->seoRepository->getById($parent, true);
        }

        $this->seoRepository->update(
            $seo,
            $updateSeoData,
            $category,
            $parent
        );

        return $seo->refresh();
    }

    public function deleteSeo(int $id): void
    {
        $seo = $this->seoRepository->getById($id, true);
        $this->seoRepository->delete($seo);
    }
}
