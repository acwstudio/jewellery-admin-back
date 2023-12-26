<?php

declare(strict_types=1);

namespace App\Modules\Collections\Support\DataProvider;

interface DataProviderInterface
{
    public function getRawData(): iterable;
}
