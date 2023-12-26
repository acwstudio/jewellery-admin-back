<?php

declare(strict_types=1);

namespace App\Modules\OTP;

use App\Packages\ModuleClients\OtpModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class OtpModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        OtpModuleClientInterface::class => OtpModuleClient::class,
    ];
}
