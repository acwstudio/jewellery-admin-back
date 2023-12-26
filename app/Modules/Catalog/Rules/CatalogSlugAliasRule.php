<?php

namespace App\Modules\Catalog\Rules;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\CategorySlugAlias;
use Illuminate\Contracts\Validation\Rule;

class CatalogSlugAliasRule implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (
            Category::query()->where('slug', $value)->exists() ||
            CategorySlugAlias::query()->where('slug', $value)->exists()
        ) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The slug is must be unique.';
    }
}
