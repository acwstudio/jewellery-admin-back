<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Pipelines;

use App\Modules\Catalog\Contracts\Pipelines\CategoryQueryBuilderPipelineContract;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;

class CategoryQueryBuilderPipeline extends Pipeline implements CategoryQueryBuilderPipelineContract
{
    public function __construct(Container $container = null, array $pipes = [])
    {
        parent::__construct($container);
        $this->pipes = $pipes;
    }
}
