<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Helpers;

use App\Modules\Catalog\Enums\CategoryListOptionsEnum;
use App\Modules\Catalog\Enums\CategoryOptionsEnum;
use App\Packages\DataObjects\Catalog\Category\CategoryListOptionsData;
use App\Packages\DataObjects\Catalog\Category\CategoryOptionsData;

class CatalogHelper
{
    public static function formatOptionsWith($options): ?CategoryOptionsData
    {
        if ($options?->with) {
            $with = [];

            foreach ($options->with as $item) {
                $with[] = CategoryOptionsEnum::from($item);
            }

            return new CategoryOptionsData($with);
        }
        return $options;
    }

    public static function formatOptionsWithCategoryList($options): ?CategoryListOptionsData
    {
        if ($options?->with) {
            $with = [];

            foreach ($options->with as $item) {
                $with[] = CategoryListOptionsEnum::from($item);
            }

            return new CategoryListOptionsData($with);
        }
        return $options;
    }
}
