<?php

declare(strict_types=1);

namespace App\Modules\Users\Support\DataProvider;

use App\Modules\Users\Support\DataProvider\UsersDataProviderInterface;
use App\Packages\ModuleClients\MonolithModuleClientInterface;
use Illuminate\Support\Collection;

class UsersDataProvider implements UsersDataProviderInterface
{
    public function __construct(
        private readonly MonolithModuleClientInterface $monolithModuleClient,
    ) {
    }

    public function getRawData(): iterable
    {
        $response = $this->monolithModuleClient->getUsers();
        return (array)$response;
    }
}
