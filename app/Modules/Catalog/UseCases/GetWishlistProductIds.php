<?php

declare(strict_types=1);

namespace App\Modules\Catalog\UseCases;

use App\Packages\ModuleClients\UsersModuleClientInterface;

class GetWishlistProductIds
{
    public function __construct(
        private readonly UsersModuleClientInterface $usersModuleClient
    ) {
    }

    public function __invoke(): array
    {
        $wishlistProducts = $this->usersModuleClient->getWishlistCollection();
        return $wishlistProducts->pluck('product_id')->toArray();
    }
}
