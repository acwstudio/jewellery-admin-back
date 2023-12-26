<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\DaData\Contracts;

use App\Packages\ApiClients\DaData\Responses\SuggestAddressResponseData;

interface DaDataApiClientContract
{
    public function getSuggestAddress(string $address): SuggestAddressResponseData;
}
