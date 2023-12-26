<?php

declare(strict_types=1);

namespace App\Providers;

use App\Modules\Users\Models\PersonalAccessToken;
use App\Packages\ApiClients\DaData\Contracts\DaDataApiClientContract;
use App\Packages\ApiClients\DaData\DaDataApiClient;
use App\Packages\ApiClients\Enterprise1C\Contracts\Enterprise1CApiClientContract;
use App\Packages\ApiClients\Enterprise1C\Enterprise1CApiClient;
use App\Packages\ApiClients\Mindbox\Contracts\MindboxApiClientContract;
use App\Packages\ApiClients\Mindbox\MindboxApiClient;
use App\Packages\ApiClients\OAuth\Contracts\OAuthApiClientContract;
use App\Packages\ApiClients\OAuth\OAuthApiClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(DaDataApiClientContract::class, DaDataApiClient::class);
        $this->app->singleton(OAuthApiClientContract::class, OAuthApiClient::class);
        $this->app->singleton(Enterprise1CApiClientContract::class, Enterprise1CApiClient::class);
        $this->app->singleton(MindboxApiClientContract::class, MindboxApiClient::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (str_starts_with(env('APP_URL', ''), 'https')) {
            URL::forceScheme('https');
        }

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        $this->registerHttpMonolithMacro();
        $this->registerEnterprise1CMacro();
        $this->registerEnterprise1CWebCCMacro();
        $this->registerIboxMacro();
        $this->registerYandexMacro();
        $this->registerYandexInfoMacro();
        $this->registerIntellinSmsMacro();
        $this->registerDaDataMacro();
        $this->registerRecaptchaMacro();
        $this->registerRapportoMacro();
    }

    private function registerHttpMonolithMacro(): void
    {
        Http::macro('monolith', function () {
            $client = Http::baseUrl('https://uvi.ru/api')->withToken(env('UVI_MONOLITH_TOKEN'));
            if (!is_null(env('UVI_MONOLITH_BRANCH'))) {
                $client = $client->withHeaders(['Cookie' => 'branch=' . env('UVI_MONOLITH_BRANCH')]);
            }
            return $client;
        });
    }

    private function registerIboxMacro(): void
    {
        Http::macro('ibox', function () {
            return Http::baseUrl(env('IBOX_URL'))->withBasicAuth(env("IBOX_LOGIN"), env("IBOX_PASS"));
        });
    }

    private function registerEnterprise1CMacro(): void
    {
        Http::macro('enterprise1C', function () {
            return Http::baseUrl(env("API_1C"))->withBasicAuth(env('API_1C_USER'), env("API_1C_PASS"))
                ->withoutVerifying();
        });
    }

    private function registerEnterprise1CWebCCMacro(): void
    {
        Http::macro('enterprise1CWebCC', function () {
            return Http::baseUrl(env("API_1C"))
                ->withBasicAuth(env('API_1C_USER_WEBCC'), env("API_1C_PASS_WEBCC"));
        });
    }

    private function registerYandexMacro(): void
    {
        Http::macro('yandex', function () {
            return Http::baseUrl(env('YANDEX_URL'))->withToken(env('YANDEX_TOKEN'));
        });
    }

    private function registerYandexInfoMacro(): void
    {
        Http::macro('yandexInfo', function () {
            return Http::baseUrl(env('YANDEX_INFO_URL', 'https://login.yandex.ru'));
        });
    }

    private function registerIntellinSmsMacro(): void
    {
        Http::macro('intellin', function () {
            return Http::baseUrl(env('INTELLIN_SMS_URL'));
        });
    }

    private function registerDaDataMacro(): void
    {
        Http::macro('daData', function () {
            return Http::baseUrl(env("DADATA_API"))
                ->withToken(env('DADATA_TOKEN'), 'Token')
                ->withHeaders(['X-Secret' => env('DADATA_SECRET')]);
        });
    }

    private function registerRecaptchaMacro(): void
    {
        Http::macro('recaptcha', function () {
            return Http::asForm()
                ->baseUrl(env('RECAPTCHA_URL', 'https://www.google.com/recaptcha/api'));
        });
    }

    private function registerRapportoMacro(): void
    {
        Http::macro('rapporto', function () {
            return Http::asForm()->baseUrl(env('RAPPORTO_URL'));
        });
    }
}
