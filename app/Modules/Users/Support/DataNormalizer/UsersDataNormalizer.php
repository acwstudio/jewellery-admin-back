<?php

declare(strict_types=1);

namespace App\Modules\Users\Support\DataNormalizer;

use App\Modules\Users\Support\DataNormalizer\UsersDataNormalizerInterface;
use App\Packages\DataObjects\Users\User\ImportUsersData;

class UsersDataNormalizer implements UsersDataNormalizerInterface
{
    public function normalize($rawData): ImportUsersData
    {
        return ImportUsersData::from($rawData);
    }
}
