<?php

declare(strict_types=1);

namespace App\Http\Controllers\Live;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Live\Setting\CreateSettingListData;
use App\Packages\ModuleClients\LiveModuleClientInterface;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;

class SettingController extends Controller
{
    public function __construct(
        protected readonly LiveModuleClientInterface $liveModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/live/setting',
        summary: 'Получить список настроек Прямого эфира',
        tags: ['Live'],
        responses: [
            new Response(
                response: 200,
                description: 'Список настроек Прямого эфира',
                content: new JsonContent(type: 'array', items: new Items(
                    ref: '#/components/schemas/live_setting_data'
                ))
            )
        ]
    )]
    public function getList(): Collection
    {
        return $this->liveModuleClient->getSettings();
    }

    #[Post(
        path: '/api/v1/live/setting',
        summary: 'Создание/обновление настроек Прямого эфира',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/live_create_setting_data')
        ),
        tags: ['Live'],
        responses: [
            new Response(
                response: 200,
                description: 'Список настроек Прямого эфира',
                content: new JsonContent(type: 'array', items: new Items(
                    ref: '#/components/schemas/live_setting_data'
                ))
            )
        ]
    )]
    public function createOrUpdate(CreateSettingListData $data): Collection
    {
        return $this->liveModuleClient->createOrUpdateSettings($data);
    }
}
