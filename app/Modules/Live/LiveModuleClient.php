<?php

declare(strict_types=1);

namespace App\Modules\Live;

use App\Modules\Live\Enums\SettingNameEnum;
use App\Modules\Live\Models\Setting;
use App\Modules\Live\Services\LiveProductListItemService;
use App\Modules\Live\Services\LiveProductService;
use App\Modules\Live\Services\SettingService;
use App\Modules\Live\Support\Blueprints\LiveProductBlueprint;
use App\Modules\Live\Support\Blueprints\SettingBlueprint;
use App\Modules\Live\Support\Filters\SettingFilter;
use App\Modules\Live\Support\Pagination;
use App\Modules\Live\UseCases\GetLiveProducts;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Live\Broadcast\BroadcastData;
use App\Packages\DataObjects\Live\LiveProduct\CreateLiveProductData;
use App\Packages\DataObjects\Live\LiveProduct\GetLiveProductListData;
use App\Packages\DataObjects\Live\LiveProduct\LiveProductListData;
use App\Packages\DataObjects\Live\LiveProduct\LiveProductListItemListData;
use App\Packages\DataObjects\Live\LiveProduct\ShortLiveProductListData;
use App\Packages\DataObjects\Live\Setting\CreateSettingData;
use App\Packages\DataObjects\Live\Setting\CreateSettingListData;
use App\Packages\DataObjects\Live\Setting\SettingData;
use App\Packages\ModuleClients\LiveModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

final class LiveModuleClient implements LiveModuleClientInterface
{
    public function __construct(
        private readonly SettingService $settingService,
        private readonly LiveProductService $liveProductService,
        private readonly LiveProductListItemService $liveProductListItemService
    ) {
    }

    public function getSettings(): Collection
    {
        $settingNameEnum = SettingNameEnum::cases();
        $settings = $this->settingService->getSettings(new SettingFilter());

        /** @var Collection<SettingData> $items */
        $items = $settings->map(
            fn (Setting $setting) => SettingData::fromModel($setting)
        );

        foreach ($settingNameEnum as $name) {
            if ($items->contains('name', $name)) {
                continue;
            }
            $items->add(new SettingData(0, $name, null, !$name->editable()));
        }

        return $items;
    }

    public function createOrUpdateSettings(CreateSettingListData $data): Collection
    {
        /** @var CreateSettingData $setting */
        foreach ($data->settings as $setting) {
            if (!$setting->name->editable()) {
                continue;
            }

            $settingBlueprint = new SettingBlueprint(
                $setting->name,
                $setting->value
            );

            $this->settingService->createOrUpdateSetting($settingBlueprint);
        }

        return $this->getSettings();
    }

    public function getLiveProducts(GetLiveProductListData $data): LiveProductListData
    {
        return App::call(GetLiveProducts::class, [$data]);
    }

    public function getShortLiveProducts(GetLiveProductListData $data): ShortLiveProductListData
    {
        $paginator = $this->liveProductService->getLiveProducts(
            new Pagination(
                $data->pagination?->page,
                $data->pagination?->per_page
            )
        );

        return ShortLiveProductListData::fromPaginator($paginator);
    }

    public function getLiveProductListItems(?PaginationData $data = null): LiveProductListItemListData
    {
        $paginator = $this->liveProductListItemService->getList(
            new Pagination(
                $data?->page,
                $data?->per_page
            )
        );

        return LiveProductListItemListData::fromPaginator($paginator);
    }

    public function createLiveProduct(CreateLiveProductData $data): void
    {
        $expired_at = $data->expired_at ?? (clone $data->started_at)->addDays(config('live.product.expire_days'));
        $this->liveProductService->createOrUpdateLiveProduct(
            new LiveProductBlueprint($data->product_id, $data->number, $data->started_at, $expired_at, $data->on_live)
        );
    }

    public function getBroadcast(): BroadcastData
    {
        $url = null;

        $setting = $this->settingService->getSettings(
            new SettingFilter(name: SettingNameEnum::URL->value)
        )->first();

        if ($setting instanceof Setting) {
            $url = $setting->value;
        }

        return new BroadcastData($url);
    }

    public function unsetOnLiveProducts(): void
    {
        $this->liveProductService->unsetOnLiveProducts();
    }
}
