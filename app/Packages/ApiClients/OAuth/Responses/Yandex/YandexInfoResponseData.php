<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\OAuth\Responses\Yandex;

use App\Packages\ApiClients\OAuth\Enums\Yandex\SexEnum;
use Spatie\LaravelData\Data;

class YandexInfoResponseData extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $client_id,
        public readonly string $psuid,
        public readonly string $login,
        public readonly ?string $first_name,
        public readonly ?string $last_name,
        public readonly ?string $display_name,
        public readonly ?array $emails,
        public readonly ?string $default_email,
        public readonly ?DefaultPhoneData $default_phone,
        public readonly ?string $real_name,
        public readonly ?bool $is_avatar_empty,
        public readonly ?string $birthday,
        public readonly ?string $default_avatar_id,
        public readonly ?string $old_social_login,
        public readonly ?SexEnum $sex,
    ) {
    }
}
