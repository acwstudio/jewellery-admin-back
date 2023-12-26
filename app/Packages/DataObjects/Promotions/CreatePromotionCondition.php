<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class CreatePromotionCondition extends Data
{
    /**
     * @param DataCollection<CreatePromotionConditionRule> $rules
     */
    public function __construct(
        #[MapInputName('URLReference')]
        #[MapOutputName('url_reference')]
        public readonly string $urlReference,
        #[MapOutputName('promo_agent')]
        public readonly string $promoAgent,
        #[MapInputName('types')]
        #[DataCollectionOf(CreatePromotionConditionRule::class)]
        public readonly DataCollection $rules,
        #[MapInputName('startsOn')]
        #[MapOutputName('start_at')]
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s',)]
        public readonly ?Carbon $startAt = null,
        #[MapInputName('endsOn')]
        #[MapOutputName('finish_at')]
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s')]
        public readonly ?Carbon $finishAt = null,
    ) {
    }
}
