<?php

declare(strict_types=1);

namespace App\Modules\Users\Support\DataProvider;

interface UsersDataProviderInterface
{
    public function getRawData(): iterable;
}
