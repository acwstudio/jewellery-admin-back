<?php

declare(strict_types=1);

namespace App\Modules\Rules;

use App\Packages\ModuleClients\RuleModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class RuleModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        RuleModuleClientInterface::class => RuleModuleServiceProvider::class,
    ];
}
