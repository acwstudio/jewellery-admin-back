<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Live\Setting;

use App\Modules\Live\Enums\SettingNameEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'live_create_setting_data', type: 'object')]
class CreateSettingData extends Data
{
    public function __construct(
        #[Property(property: 'name', type: 'string')]
        public readonly SettingNameEnum $name,
        #[Property(property: 'value', type: 'string', nullable: true)]
        public readonly ?string $value
    ) {
    }
}
