<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Live\Setting;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(schema: 'live_create_setting_list_data', type: 'object')]
class CreateSettingListData extends Data
{
    public function __construct(
        #[Property(
            property: 'settings',
            type: 'array',
            items: new Items(ref: '#/components/schemas/live_create_setting_data')
        )]
        #[DataCollectionOf(CreateSettingData::class)]
        public readonly ?DataCollection $settings,
    ) {
    }
}
