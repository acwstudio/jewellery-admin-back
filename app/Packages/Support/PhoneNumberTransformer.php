<?php

declare(strict_types=1);

namespace App\Packages\Support;

use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberTransformer
{
    public static function parse(string $phone): PhoneNumber
    {
        /** @var PhoneNumber $phone */
        $phone = PhoneNumberUtil::getInstance()->parse(
            $phone,
            'RU',
            new PhoneNumber()
        );

        return $phone;
    }

    public static function format(PhoneNumber $phone): string
    {
        return PhoneNumberUtil::getInstance()->format(
            $phone,
            PhoneNumberFormat::E164
        );
    }
}
