<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Packages\DataObjects\Checkout\CalculateData;
use App\Packages\DataObjects\Checkout\CheckoutData;
use App\Packages\DataObjects\Checkout\Summary\GetSummaryData;
use App\Packages\DataObjects\Checkout\Summary\SummaryData;
use Illuminate\Support\Collection;

interface CheckoutModuleClientInterface
{
    public function getCheckout(): CheckoutData;

    public function calculate(?Collection $items = null): CalculateData;

    public function getSummary(GetSummaryData $data): SummaryData;
}
