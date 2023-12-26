<?php

declare(strict_types=1);

namespace App\Packages\AttributeCasts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Packages\Support\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberCast implements CastsAttributes
{
    public function __construct(
        protected string $region = 'RU'
    ) {
    }

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        return PhoneNumberUtil::getInstance()->parse($value, $this->getRegion(), new PhoneNumber());
    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        if (! $value instanceof PhoneNumber) {
            throw new \InvalidArgumentException('The value of type libphonenumber\PhoneNumber expected');
        }

        return PhoneNumberUtil::getInstance()->format($value, PhoneNumberFormat::E164);
    }

    public function getRegion(): string
    {
        return $this->region;
    }
}
