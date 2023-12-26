<?php

use App\Modules\Catalog\Models\Product;

return [
    Product::class => [
        'default_per_page' => (int)env('PAGINATION_PRODUCT_DEFAULT_PER_PAGE', 32)
    ]
];
