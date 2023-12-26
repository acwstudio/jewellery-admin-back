<?php

declare(strict_types=1);

namespace App\Packages\Support;

use App\Packages\AttributeCasts\PhoneNumberCast;
use Illuminate\Contracts\Database\Eloquent\Castable;

class PhoneNumber extends \libphonenumber\PhoneNumber implements Castable
{
    /**
     * @inheritDoc
     */
    public static function castUsing(array $arguments)
    {
        return PhoneNumberCast::class;
    }
}
