<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Users\User;

use App\Modules\Users\Enums\SexTypeEnum;
use App\Packages\DataCasts\PhoneNumberCast;
use App\Packages\Support\FilterQuery\Attributes\Nullable;
use App\Packages\Support\PhoneNumber;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'users_user_update_profile_data',
    description: 'Обновление данных профиля пользователя',
    type: 'object'
)]
class UpdateUserProfileData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(property: 'phone', type: 'string')]
        #[Required, StringType, WithCast(PhoneNumberCast::class)]
        public PhoneNumber $phone,
        #[Property(property: 'name', type: 'string')]
        #[Required, StringType]
        public string $name,
        #[Property(property: 'email', type: 'string', nullable: true)]
        #[Nullable, StringType]
        public ?string $email = null,
        #[Property(property: 'sex', type: 'string', nullable: true)]
        #[Nullable, Enum(SexTypeEnum::class)]
        public ?SexTypeEnum $sex = null,
        #[Property(property: 'birth_date', type: 'string', nullable: true)]
        #[Nullable, Date]
        public ?string $birth_date = null,
        #[Property(property: 'surname', type: 'string', nullable: true)]
        #[Nullable, StringType]
        public ?string $surname = null,
        #[Property(property: 'patronymic', type: 'string', nullable: true)]
        #[Nullable, StringType]
        public ?string $patronymic = null,
        #[Property(property: 'old_password', type: 'string', nullable: true)]
        #[Nullable, StringType]
        public ?string $old_password = null,
        #[Property(property: 'new_password', type: 'string', nullable: true)]
        #[Nullable, StringType]
        public ?string $new_password = null
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
