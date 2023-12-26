<?php

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\CategorySlugAlias;

class CategorySlugAliasRepository
{
    public function createCategorySlugAlias(Category $category, string $slug): CategorySlugAlias
    {
        return $category->slugAliases()->create([
            'slug' => $slug
        ]);
    }
}
