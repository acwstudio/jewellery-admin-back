<?php

declare(strict_types=1);

namespace App\Http\Controllers\Live;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Live\Broadcast\BroadcastData;
use App\Packages\ModuleClients\LiveModuleClientInterface;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response;

class BroadcastController extends Controller
{
    public function __construct(
        protected readonly LiveModuleClientInterface $liveModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/live/broadcast',
        summary: 'Получить данные Прямого эфира',
        tags: ['Live'],
        responses: [
            new Response(
                response: 200,
                description: 'Данные Прямого эфира',
                content: new JsonContent(type: 'array', items: new Items(
                    ref: '#/components/schemas/live_broadcast_data'
                ))
            )
        ]
    )]
    public function get(): BroadcastData
    {
        return $this->liveModuleClient->getBroadcast();
    }
}
