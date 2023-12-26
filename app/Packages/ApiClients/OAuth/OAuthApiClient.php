<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\OAuth;

use App\Packages\ApiClients\OAuth\Contracts\OAuthApiClientContract;
use App\Packages\ApiClients\OAuth\Responses\Yandex\YandexInfoResponseData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class OAuthApiClient implements OAuthApiClientContract
{
    public function yandexInfo(string $token): YandexInfoResponseData
    {
        /** @var Response $response */
        /** @phpstan-ignore-next-line */
        $response = Http::yandexInfo()->withToken($token, 'OAuth')->get('/info');

        if ($response->unauthorized()) {
            throw new \Exception('Ошибка авторизации YandexID');
        }

        if ($response->failed()) {
            throw new \Exception('Ошибка получения ответа от Yandex');
        }

        return YandexInfoResponseData::from(
            $response->json()
        );
    }
}
