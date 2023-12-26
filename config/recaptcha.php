<?php

declare(strict_types=1);

return [
    'debug_mode' => (bool) env('RECAPTCHA_DEBUG_MODE', false),
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    'site_key' => env('RECAPTCHA_SITE_KEY'),
];
