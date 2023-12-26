<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Users\User;

use App\Modules\Users\Enums\SexTypeEnum;
use App\Modules\Users\Models\User;
use App\Packages\DataTransformers\PhoneNumberTransformer;
use App\Packages\Support\FilterQuery\Attributes\Nullable;
use App\Packages\Support\PhoneNumber;
use Carbon\Carbon;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'users_user_profile_data',
    description: 'Данные профиля пользователя',
    type: 'object'
)]
class UserProfileData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'string')]
        public string $id,
        #[Property(property: 'name', type: 'string')]
        public string $name,
        #[Property(property: 'surname', type: 'string', nullable: true)]
        public ?string $surname = null,
        #[Property(property: 'patronymic', type: 'string', nullable: true)]
        public ?string $patronymic = null,
        #[Property(property: 'phone', type: 'string', nullable: true)]
        #[WithTransformer(PhoneNumberTransformer::class)]
        public ?PhoneNumber $phone = null,
        #[Property(property: 'email', type: 'string', nullable: true)]
        public ?string $email = null,
        #[Property(property: 'sex', type: 'string', nullable: true)]
        public SexTypeEnum|null $sex = null,
        #[Property(property: 'birth_date', type: 'string', nullable: true)]
        public ?Carbon $birth_date = null
    ) {
    }

    public static function fromModel(User $user): self
    {
        return new self(
            $user->user_id,
            $user->name ?? 'Пользователь',
            $user->surname,
            $user->patronymic,
            $user->phone,
            $user->email,
            $user->sex,
            $user->birth_date
        );
    }
}
