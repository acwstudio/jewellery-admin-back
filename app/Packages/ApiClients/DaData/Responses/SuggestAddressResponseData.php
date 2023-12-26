<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\DaData\Responses;

use App\Packages\ApiClients\DaData\Responses\DataObjects\SuggestAddressData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class SuggestAddressResponseData extends Data
{
    public function __construct(
        #[DataCollectionOf(SuggestAddressData::class)]
        public readonly DataCollection $suggestions
    ) {
    }
}
