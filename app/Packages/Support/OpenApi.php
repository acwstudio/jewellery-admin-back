<?php

declare(strict_types=1);

namespace App\Packages\Support;

use OpenApi\Attributes\Info;
use OpenApi\Attributes\Server;
use OpenApi\Attributes\Tag;

#[Info(
    version: '0.1',
    title: 'Checkout Back API',
)]
#[Tag(name: 'Catalog')]
#[Server('https://http://core.test.tvuvi.ru/', description: 'Test Env')]
final class OpenApi
{
    private function __construct()
    {
    }
}
