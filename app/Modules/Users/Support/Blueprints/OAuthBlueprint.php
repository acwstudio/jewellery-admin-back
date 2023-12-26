<?php

declare(strict_types=1);

namespace App\Modules\Users\Support\Blueprints;

use App\Packages\ApiClients\OAuth\Enums\OAuthTypeEnum;

class OAuthBlueprint
{
    public function __construct(
        public readonly string $token,
        public readonly OAuthTypeEnum $type,
    ) {
    }
}
