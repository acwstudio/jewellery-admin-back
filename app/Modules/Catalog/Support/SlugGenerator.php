<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support;

use App\Modules\Catalog\Models\Category;
use Illuminate\Support\Str;

class SlugGenerator
{
    public function create(?Category $category = null, string $separator = '-'): string
    {
        return $this->createWithParent($category->title, $category->parent, $separator);
    }

    public function createWithParent(string $title, ?Category $parent = null, string $separator = '-'): string
    {
        $slug = Str::slug($title, $separator);

        if (null === $parent) {
            return $slug;
        }

        return $this->createWithParent($parent->title, $parent->parent, $separator) . $separator . $slug;
    }

    public function createForProduct(string $name, string $sku, string $separator = '-'): string
    {
        $name = Str::slug($name, $separator);
        $sku = Str::slug($sku, $separator);

        return $name . '_' . $sku;
    }
}
