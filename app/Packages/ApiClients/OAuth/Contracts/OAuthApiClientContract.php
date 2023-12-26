<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\OAuth\Contracts;

use App\Packages\ApiClients\OAuth\Responses\Yandex\YandexInfoResponseData;

interface OAuthApiClientContract
{
    public function yandexInfo(string $token): YandexInfoResponseData;
}
