<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Mindbox\Requests\WebsiteSetWithList;

use App\Packages\ApiClients\Mindbox\Requests\Common\ProductListItemData;
use Spatie\LaravelData\Data;

class CreateWebsiteSetWithListData extends Data
{
    public function __construct(
        /** @var ProductListItemData[] $productList */
        public readonly array $productList
    ) {
    }
}
