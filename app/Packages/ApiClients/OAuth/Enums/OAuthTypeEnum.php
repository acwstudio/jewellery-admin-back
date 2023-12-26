<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\OAuth\Enums;

use OpenApi\Attributes\Schema;

#[Schema(type: 'string')]
enum OAuthTypeEnum: string
{
    case YANDEX = 'yandex';
}
