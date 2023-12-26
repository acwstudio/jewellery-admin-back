<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Live\Setting;

use App\Modules\Live\Enums\SettingNameEnum;
use App\Modules\Live\Models\Setting;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'live_setting_data', type: 'object')]
class SettingData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'name', type: 'string')]
        public readonly SettingNameEnum $name,
        #[Property(property: 'value', type: 'string', nullable: true)]
        public readonly ?string $value,
        #[Property(property: 'disabled', type: 'boolean')]
        public readonly bool $disabled
    ) {
    }

    public static function fromModel(Setting $setting): self
    {
        return new self(
            $setting->id,
            $setting->name,
            $setting->value,
            !$setting->name->editable()
        );
    }
}
