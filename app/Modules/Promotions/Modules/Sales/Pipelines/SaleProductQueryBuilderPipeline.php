<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Pipelines;

use App\Modules\Promotions\Modules\Sales\Contracts\Pipelines\SaleProductQueryBuilderPipelineContract;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;

class SaleProductQueryBuilderPipeline extends Pipeline implements SaleProductQueryBuilderPipelineContract
{
    public function __construct(Container $container = null, array $pipes = [])
    {
        parent::__construct($container);
        $this->pipes = $pipes;
    }
}
