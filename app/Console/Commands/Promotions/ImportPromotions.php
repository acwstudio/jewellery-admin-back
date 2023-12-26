<?php

declare(strict_types=1);

namespace App\Console\Commands\Promotions;

use App\Console\Command;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;

class ImportPromotions extends Command
{
    protected $signature = 'import:promotions';

    public function __construct(
        private readonly PromotionsModuleClientInterface $promotionsModuleClient
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->promotionsModuleClient->importPromotions();
        return self::SUCCESS;
    }
}
