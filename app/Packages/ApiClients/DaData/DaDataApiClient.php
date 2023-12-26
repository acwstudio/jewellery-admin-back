<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\DaData;

use App\Packages\ApiClients\DaData\Contracts\DaDataApiClientContract;
use App\Packages\ApiClients\DaData\Responses\SuggestAddressResponseData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class DaDataApiClient implements DaDataApiClientContract
{
    public function getSuggestAddress(string $address): SuggestAddressResponseData
    {
        $body = [
            'query' => $address
        ];

        /** @var Response $response */
        $response = Http::daData()->post('/suggest/address', $body);

        return SuggestAddressResponseData::from(
            $response->json()
        );
    }
}
