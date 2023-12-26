<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Modules\XmlFeed\Enums\FeedTypeEnum;

interface XmlFeedModuleClientInterface
{
    public function generate(FeedTypeEnum $type, ?callable $onEach = null): void;
}
