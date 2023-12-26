<?php

declare(strict_types=1);

namespace App\Modules\XmlFeed\UseCases;

use App\Packages\DataObjects\Catalog\Category\CategoryListOptionsData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;

class GetProductCategories
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    public function __invoke(?CategoryListOptionsData $data = null): Collection
    {
        if (!$data instanceof CategoryListOptionsData) {
            $data = new CategoryListOptionsData();
        }
        return $this->catalogModuleClient->getCategoryList($data);
    }
}
