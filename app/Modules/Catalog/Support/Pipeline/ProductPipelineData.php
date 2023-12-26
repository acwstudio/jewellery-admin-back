<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Pipeline;

use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use OpenSearch\ScoutDriverPlus\Builders\SearchParametersBuilder;
use Spatie\LaravelData\Data;

class ProductPipelineData extends Data
{
    public function __construct(
        public readonly SearchParametersBuilder $builder,
        public readonly ProductGetListData $data,
    ) {
    }
}
