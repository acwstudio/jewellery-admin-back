<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Support\Pvz\DataProvider;

interface PvzDataProviderInterface
{
    public function import(\Closure $callback): void;
}
