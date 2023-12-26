<?php

namespace App\Modules\Catalog\Support\DataProvider;

interface DataProviderInterface
{
    public function getRawData(): iterable;
}
