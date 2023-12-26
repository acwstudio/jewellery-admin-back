<?php

declare(strict_types=1);

namespace App\Modules\Orders\Pipelines;

use App\Modules\Orders\Contracts\Pipelines\OrderQueryBuilderPipelineContract;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;

class OrderQueryBuilderPipeline extends Pipeline implements OrderQueryBuilderPipelineContract
{
    public function __construct(Container $container = null, array $pipes = [])
    {
        parent::__construct($container);
        $this->pipes = $pipes;
    }
}
