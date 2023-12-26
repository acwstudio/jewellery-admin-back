<?php

namespace App\Modules\Monolith\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class CategoryRepository
{
    public function getCategories(): iterable
    {
        return DB::connection('monolith_db')
            ->query()
            ->select([
                'id', 'parent_id', 'name', 'h1', 'meta_title',
                'meta_description', 'meta_keywords', 'description', 'url'
            ])
            ->from('catalog_categories')
            ->whereNull('is_archive')
            ->orderBy('id')
            ->lazy();
    }
}
