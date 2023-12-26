<?php

declare(strict_types=1);

namespace App\Modules\Users\Pipelines;

use App\Modules\Users\Contracts\Pipelines\WishlistQueryBuilderPipelineContract;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;

class WishlistQueryBuilderPipeline extends Pipeline implements WishlistQueryBuilderPipelineContract
{
    public function __construct(Container $container = null, array $pipes = [])
    {
        parent::__construct($container);
        $this->pipes = $pipes;
    }
}
