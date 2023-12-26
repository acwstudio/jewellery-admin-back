<?php

declare(strict_types=1);

namespace App\Modules\Users;

use App\Modules\Users\Contracts\Pipelines\WishlistQueryBuilderPipelineContract;
use App\Modules\Users\Models\PersonalAccessToken;
use App\Modules\Users\Pipelines\WishlistQueryBuilderPipeline;
use App\Modules\Users\Pipes\SortWishlistQueryBuilderPipe;
use App\Modules\Users\Services\UsersImportService;
use App\Modules\Users\Support\DataNormalizer\UsersDataNormalizerInterface;
use App\Modules\Users\Support\DataProvider\UsersDataProvider;
use App\Modules\Users\Support\DataProvider\UsersDataProviderInterface;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class UsersModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        UsersModuleClientInterface::class => UsersModuleClient::class,
    ];

    private array $wishlistQueryBuilderPipes = [
        SortWishlistQueryBuilderPipe::class,
    ];

    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        $this->registerQueryBuilderPipelines();
        $this->registerUsersImportService();
    }

    protected function registerQueryBuilderPipelines(): void
    {
        $this->app->singleton(
            WishlistQueryBuilderPipelineContract::class,
            WishlistQueryBuilderPipeline::class
        );
        $this->app->when(WishlistQueryBuilderPipeline::class)->needs('$pipes')->give(
            fn () => $this->wishlistQueryBuilderPipes
        );
    }

    protected function registerUsersImportService(): void
    {
        if (!$this->app->runningUnitTests()) {
            $this->app
                ->when(UsersImportService::class)
                ->needs(UsersDataProviderInterface::class)
                ->give(function () {
                    return $this->app->make(config('users.import.users.data_provider'));
                });
        }

        $this->app
            ->when(UsersImportService::class)
            ->needs(UsersDataNormalizerInterface::class)
            ->give(function () {
                return $this->app->make(config('users.import.users.data_normalizer'));
            });
    }
}
