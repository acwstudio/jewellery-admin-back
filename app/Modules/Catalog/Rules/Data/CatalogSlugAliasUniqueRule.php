<?php

namespace App\Modules\Catalog\Rules\Data;

use App\Modules\Catalog\Rules\CatalogSlugAliasRule;
use Attribute;
use Spatie\LaravelData\Support\Validation\ValidationRule;

#[Attribute(Attribute::TARGET_PROPERTY)]
class CatalogSlugAliasUniqueRule extends ValidationRule
{
    public function getRules(): array
    {
        return [new CatalogSlugAliasRule()];
    }
}
