<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Users\Auth;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'users_auth_data',
    description: 'Данные аутентификации',
    type: 'object'
)]
class AuthData extends Data
{
    public function __construct(
        #[Property(property: 'access_token', type: 'string')]
        public readonly string $access_token,
    ) {
    }
}
