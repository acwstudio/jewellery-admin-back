<?php

declare(strict_types=1);

namespace App\Modules\XmlFeed;

use App\Packages\ModuleClients\XmlFeedModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class XmlFeedModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        XmlFeedModuleClientInterface::class => XmlFeedModuleClient::class,
    ];
}
