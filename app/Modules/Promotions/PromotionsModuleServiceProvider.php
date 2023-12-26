<?php

declare(strict_types=1);

namespace App\Modules\Promotions;

use App\Modules\Promotions\Modules\Promocodes\Repository\PromocodeRepository;
use App\Modules\Promotions\Modules\Promocodes\Repository\PromocodeUsageRepository;
use App\Modules\Promotions\Modules\Sales\Contracts\Pipelines\SaleProductQueryBuilderPipelineContract;
use App\Modules\Promotions\Modules\Sales\Pipelines\SaleProductQueryBuilderPipeline;
use App\Modules\Promotions\Modules\Sales\Pipes\SaleProductQueryBuilderFilterPipe;
use App\Modules\Promotions\Modules\Promocodes\Services\PromocodeBenefitService;
use App\Modules\Promotions\Modules\Promocodes\Services\PromocodeConditionService;
use App\Modules\Promotions\Modules\Promocodes\Support\Validator\RulePromocodeValidator;
use App\Modules\Promotions\Services\ImportPromotionService;
use App\Modules\Promotions\Support\DataNormalizer\DataNormalizerInterface;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use App\Packages\ModuleClients\ShopCartModuleClientInterface;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class PromotionsModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        PromotionsModuleClientInterface::class => PromotionsModuleClient::class,
    ];

    private array $moduleQueryBuilderPipes = [
        'sales' => [
            SaleProductQueryBuilderFilterPipe::class
        ]
    ];

    public function boot(): void
    {
        $this->app->tag(
            config('promotions.promocode_condition_validators'),
            'promotions.promocode_condition_validators'
        );

        $this->app->tag(
            config('promotions.promocode_condition_rules'),
            'promotions.promocode_condition_rules'
        );

        $this->app->tag(
            config('promotions.promocode_benefit_activators'),
            'promotions.promocode_benefit_activators'
        );

        $this->app->bind(PromocodeBenefitService::class, function (Application $app) {
            return new PromocodeBenefitService(
                $app->tagged('promotions.promocode_benefit_activators'),
                $this->app->make(PromocodeRepository::class),
                $this->app->make(ShopCartModuleClientInterface::class),
                $this->app->make(UsersModuleClientInterface::class),
                $this->app->make(PromocodeUsageRepository::class)
            );
        });

        $this->app->bind(PromocodeConditionService::class, function (Application $app) {
            return new PromocodeConditionService(
                $app->tagged('promotions.promocode_condition_validators'),
            );
        });

        $this->app->bind(RulePromocodeValidator::class, function (Application $app) {
            return new RulePromocodeValidator(
                $app->make(ShopCartModuleClientInterface::class),
                $app->tagged('promotions.promocode_condition_rules'),
            );
        });

        $this->registerSalesQueryBuilderPipelines();
        $this->registerImportPromotionService();
    }

    protected function registerSalesQueryBuilderPipelines(): void
    {
        $this->app->singleton(
            SaleProductQueryBuilderPipelineContract::class,
            SaleProductQueryBuilderPipeline::class
        );
        $this->app->when(SaleProductQueryBuilderPipeline::class)->needs('$pipes')->give(
            fn () => $this->moduleQueryBuilderPipes['sales']
        );
    }

    protected function registerImportPromotionService(): void
    {
        $this->app
            ->when(ImportPromotionService::class)
            ->needs(DataNormalizerInterface::class)
            ->give(function () {
                return $this->app->make(config('promotions.import.promotion.data_normalizer'));
            });
    }
}
