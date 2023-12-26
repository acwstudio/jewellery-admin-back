<?php

declare(strict_types=1);

return [
    'static_code_enabled' => (bool)env('OTP_STATIC_CODE_ENABLED', false),
    'static_code_value' => env('OTP_STATIC_CODE_VALUE', '123456'),
    'len_code' => (int)env('OTP_LEN_CODE', 6),
    'retry_timeout' => (int)env('OTP_RETRY_TIMEOUT', 30)
];
