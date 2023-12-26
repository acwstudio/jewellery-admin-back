<?php

declare(strict_types=1);

namespace App\Packages\Enums;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Money\Money;

enum ValueFormatEnum: string
{
    case STRING = 'string';
    case STRING_NOT_FORMAT = 'string_not_format';
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case BOOLEAN = 'boolean';
    case DATETIME = 'datetime';
    case DATETIME_TIMEZONE_SERVER = 'datetime_timezone_server';
    case MONEY = 'money';
    case MONEY_DECIMAL = 'money_decimal';
    case ARRAY = 'array';

    public function format($value)
    {
        return match ($this) {
            self::STRING => $this->formatText((string)$value),
            self::STRING_NOT_FORMAT => (string)$value,
            self::INTEGER => (int)$value,
            self::FLOAT => (float)$value,
            self::BOOLEAN => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            self::DATETIME => $this->formatDatetime($value),
            self::DATETIME_TIMEZONE_SERVER => $this->formatDatetime($value, true),
            self::MONEY => Money::RUB(intval($value)),
            self::MONEY_DECIMAL => Money::RUB(intval($value) * 100),
            self::ARRAY => (array)$value,
        };
    }

    private function formatText(string $text): string
    {
        return Str::ucfirst(trim($text));
    }

    private function formatDatetime(string $datetime, bool $isTzServer = false): ?Carbon
    {
        try {
            if ($isTzServer) {
                return Carbon::parse($datetime)->setTimezone(config('app.timezone'));
            }
            return Carbon::parse($datetime);
        } catch (\Exception) {
            return null;
        }
    }
}
