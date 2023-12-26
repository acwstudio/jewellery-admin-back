<?php

declare(strict_types=1);

namespace App\Packages\Support;

use App\Packages\Enums\ValueFormatEnum;
use Illuminate\Support\Arr;

class DataArray
{
    public function __construct(
        private readonly array $data
    ) {
    }

    public function get(
        string $name,
        ValueFormatEnum $formatType = ValueFormatEnum::STRING,
        mixed $default = null
    ): mixed {
        $formatEmpty = [ValueFormatEnum::STRING];
        $value = Arr::get($this->data, $name) ?? null;
        if (in_array($formatType, $formatEmpty) && empty($value)) {
            return $default;
        } elseif (null === $value) {
            return $default;
        }

        return $formatType->format($value);
    }

    public function has(string $name): bool
    {
        return Arr::has($this->data, $name);
    }
}
