<?php

declare(strict_types=1);

namespace App\Modules\Checkout;

use App\Modules\Checkout\UseCase\Calculate;
use App\Modules\Checkout\UseCase\GetCheckout;
use App\Modules\Checkout\UseCase\GetSummary;
use App\Packages\DataObjects\Checkout\CalculateData;
use App\Packages\DataObjects\Checkout\CheckoutData;
use App\Packages\DataObjects\Checkout\Summary\GetSummaryData;
use App\Packages\DataObjects\Checkout\Summary\SummaryData;
use App\Packages\ModuleClients\CheckoutModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class CheckoutModuleClient implements CheckoutModuleClientInterface
{
    public function getCheckout(): CheckoutData
    {
        return App::call(GetCheckout::class);
    }

    public function calculate(?Collection $items = null): CalculateData
    {
        return App::call(Calculate::class, ['items' => $items]);
    }

    public function getSummary(GetSummaryData $data): SummaryData
    {
        return App::call(GetSummary::class, [$data]);
    }
}
