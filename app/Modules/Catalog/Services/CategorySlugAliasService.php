<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\CategorySlugAlias;
use App\Modules\Catalog\Repositories\CategorySlugAliasRepository;

class CategorySlugAliasService
{
    public function __construct(private CategorySlugAliasRepository $categorySlugAliasRepository)
    {
    }

    public function createCategorySlugAlias(Category $category, string $slug): CategorySlugAlias
    {
        return $this->categorySlugAliasRepository->createCategorySlugAlias($category, $slug);
    }
}
