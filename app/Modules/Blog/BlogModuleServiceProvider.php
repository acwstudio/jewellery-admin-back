<?php

declare(strict_types=1);

namespace App\Modules\Blog;

use App\Packages\ModuleClients\BlogModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class BlogModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        BlogModuleClientInterface::class => BlogModuleClient::class,
    ];
}
