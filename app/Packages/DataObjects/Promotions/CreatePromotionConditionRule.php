<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions;

use App\Modules\Promotions\Services\CreatePromotionConditionRulePhone;
use App\Packages\DataCasts\MappedEnumCast;
use App\Packages\DataCasts\MoneyCast;
use App\Packages\Enums\OperatorEnum;
use App\Packages\Support\PhoneNumber;
use Illuminate\Support\Collection;
use Money\Money;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class CreatePromotionConditionRule extends Data
{
    public function __construct(
        #[MapInputName('type')]
        public readonly string $type,
        #[MapInputName('amount')]
        #[MapOutputName('total_amount')]
        #[WithCast(MoneyCast::class, isDecimal: true)]
        public readonly ?Money $totalAmount = null,
        #[MapInputName('count')]
        #[MapOutputName('total_count')]
        public readonly ?int $totalCount = null,
        #[MapInputName('comparisonType')]
        #[WithCast(
            MappedEnumCast::class,
            map: [
                'no less than' => 'ge',
                'no more than' => 'le',
            ]
        )]
        public readonly ?OperatorEnum $operator = null,
        #[MapInputName('param')]
        #[MapOutputName('feature_name')]
        public readonly ?string $featureName = null,
        #[MapInputName('value')]
        #[MapOutputName('feature_value')]
        public readonly ?string $featureValue = null,
        #[DataCollectionOf(CreatePromotionConditionRulePhone::class)]
        public readonly ?DataCollection $phones = null
    ) {
    }
}
