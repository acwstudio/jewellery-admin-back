<?php

declare(strict_types=1);

namespace App\Modules\Users\Services;

use App\Modules\Users\Support\DataNormalizer\UsersDataNormalizerInterface;
use App\Modules\Users\Support\DataProvider\UsersDataProviderInterface;
use Generator;

class UsersImportService
{
    public function __construct(
        protected UsersDataProviderInterface $usersDataProvider,
        protected UsersDataNormalizerInterface $usersDataNormalizer
    ) {
    }

    public function import(): Generator
    {
        foreach ($this->usersDataProvider->getRawData() as $data) {
            yield $this->usersDataNormalizer->normalize($data);
        }
    }
}
