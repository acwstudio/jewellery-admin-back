<?php

declare(strict_types=1);

namespace App\Console\Commands\User;

use App\Console\Command;
use App\Packages\ModuleClients\UsersModuleClientInterface;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импортирует пользователей с монолита';

    public function __construct(
        protected UsersModuleClientInterface $usersModuleClient
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
        $bar = $this->initializeProgressBar();

        $this->info('Users importing...');

        $this->withoutTelescope(function () use ($bar) {
            $this->usersModuleClient->importUsers([$bar, 'advance']);
        });

        $bar->finish();

        $this->info("\nImport finished!");

        return Command::SUCCESS;
    }
}
