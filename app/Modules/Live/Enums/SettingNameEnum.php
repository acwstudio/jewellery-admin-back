<?php

declare(strict_types=1);

namespace App\Modules\Live\Enums;

use OpenApi\Attributes\Schema;

#[Schema(schema: 'live_setting_name_enum', type: 'string')]
enum SettingNameEnum: string
{
    case URL = 'url';
    case EXPIRED_AT = 'expired_at';

    public function editable(): bool
    {
        return match ($this) {
            self::URL => true,
            self::EXPIRED_AT => false
        };
    }
}
