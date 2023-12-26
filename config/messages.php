<?php

declare(strict_types=1);

return [
    'message_gateway' => App\Modules\Messages\MessageGateway\RapportoMessageGateway::class,

    'rapporto' => [
        'login' => env('RAPPORTO_LOGIN'),
        'password' => env('RAPPORTO_PASS'),
        'uri' => env('RAPPORTO_URI'),
        'service_number' => env('RAPPORTO_SERVICE_NUMBER'),
    ],
    'mails' => [
        'no_size' => env('MAIL_SUPPORT_NO_SIZE', 'aperevalov@ves-media.com'),
    ]
];
