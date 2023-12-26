<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Contracts\Pipes;

use App\Modules\Catalog\Support\Pipeline\ProductPipelineData;
use Closure;

interface ProductScoutBuilderPipeContract
{
    public function handle(ProductPipelineData $pipelineData, Closure $next): ProductPipelineData;
}
