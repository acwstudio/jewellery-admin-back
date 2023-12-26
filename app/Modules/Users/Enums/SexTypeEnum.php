<?php

declare(strict_types=1);

namespace App\Modules\Users\Enums;

enum SexTypeEnum: string
{
    case MALE = '1';
    case FEMALE = '2';

    public static function values(): array
    {
        return [
            self::MALE->value,
            self::FEMALE->value
        ];
    }
}
