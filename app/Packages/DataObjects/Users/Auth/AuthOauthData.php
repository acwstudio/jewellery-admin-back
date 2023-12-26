<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Users\Auth;

use App\Packages\ApiClients\OAuth\Enums\OAuthTypeEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

#[Schema(
    schema: 'auth_oauth_data',
    description: 'Авторизация OAuth',
    type: 'object'
)]
class AuthOauthData extends Data
{
    public function __construct(
        #[Property(property: 'token', type: 'string')]
        public readonly string $token,
        #[Property(property: 'type', default: OAuthTypeEnum::YANDEX)]
        public readonly OAuthTypeEnum|Optional $type = OAuthTypeEnum::YANDEX
    ) {
    }
}
