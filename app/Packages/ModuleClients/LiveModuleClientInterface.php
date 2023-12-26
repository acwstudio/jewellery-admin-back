<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Live\Broadcast\BroadcastData;
use App\Packages\DataObjects\Live\LiveProduct\CreateLiveProductData;
use App\Packages\DataObjects\Live\LiveProduct\GetLiveProductListData;
use App\Packages\DataObjects\Live\LiveProduct\LiveProductListData;
use App\Packages\DataObjects\Live\LiveProduct\LiveProductListItemListData;
use App\Packages\DataObjects\Live\LiveProduct\ShortLiveProductListData;
use App\Packages\DataObjects\Live\Setting\CreateSettingListData;
use Illuminate\Support\Collection;

interface LiveModuleClientInterface
{
    public function getSettings(): Collection;

    public function createOrUpdateSettings(CreateSettingListData $data): Collection;

    public function getLiveProducts(GetLiveProductListData $data): LiveProductListData;

    public function getShortLiveProducts(GetLiveProductListData $data): ShortLiveProductListData;

    public function getLiveProductListItems(?PaginationData $data = null): LiveProductListItemListData;

    public function createLiveProduct(CreateLiveProductData $data): void;

    public function getBroadcast(): BroadcastData;

    public function unsetOnLiveProducts(): void;
}
