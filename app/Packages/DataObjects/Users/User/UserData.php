<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Users\User;

use App\Modules\Users\Enums\AuthTokenNameEnum;
use App\Modules\Users\Models\User;
use App\Packages\DataObjects\Users\Auth\AuthData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'users_user_data',
    description: 'Данные пользователя',
    type: 'object'
)]
class UserData extends Data
{
    public function __construct(
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'auth', ref: '#/components/schemas/users_auth_data', type: 'object')]
        public readonly AuthData $auth,
    ) {
    }

    public static function fromModel(User $user): self
    {
        $token = $user->createToken(AuthTokenNameEnum::ACCESS_TOKEN->value);
        $authData = AuthData::from([
            'access_token' => $token->plainTextToken
        ]);

        return new self(
            $user->name ?? 'Пользователь',
            $authData
        );
    }
}
