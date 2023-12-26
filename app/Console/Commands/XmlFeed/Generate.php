<?php

declare(strict_types=1);

namespace App\Console\Commands\XmlFeed;

use App\Console\Command;
use App\Modules\XmlFeed\Enums\FeedTypeEnum;
use App\Packages\ModuleClients\XmlFeedModuleClientInterface;

class Generate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:xml_feed {feedType}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Генерация XML фида';

    public function __construct(
        protected XmlFeedModuleClientInterface $xmlFeedModuleClient
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $feedType = FeedTypeEnum::from($this->argument('feedType'));

        $this->info('XML Feed generating...');

        $this->withoutTelescope(function () use ($feedType) {
            $this->xmlFeedModuleClient->generate($feedType);
        });

        $this->info("\nGenerate finished!");

        return Command::SUCCESS;
    }
}
