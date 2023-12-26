<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Pipelines;

use App\Modules\Catalog\Contracts\Pipelines\ProductQueryBuilderPipelineContract;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;

class ProductQueryBuilderPipeline extends Pipeline implements ProductQueryBuilderPipelineContract
{
    public function __construct(Container $container = null, array $pipes = [])
    {
        parent::__construct($container);
        $this->pipes = $pipes;
    }
}
