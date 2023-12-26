<?php

declare(strict_types=1);

namespace App\Modules\Users\Support\DataNormalizer;

use App\Packages\DataObjects\Users\User\ImportUsersData;

interface UsersDataNormalizerInterface
{
    public function normalize(array $rawData): ImportUsersData;
}
