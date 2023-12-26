<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Messages\CreateOrder;

use App\Packages\DataTransformers\BoolToIntTransformer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class CreateOrderMessageData extends Data
{
    public function __construct(
        #[MapName('order_id')]
        public readonly string $id,
        public readonly string $project,
        public readonly string $country,
        #[MapName('payment_type')]
        public readonly int $paymentType,
        #[MapName('created_at')]
        public readonly Carbon $createdAt,
        #[MapName('is_employee')]
        #[WithTransformer(BoolToIntTransformer::class)]
        public readonly bool $isEmployee,
        public readonly CreateOrderMessageAddressData $address,
        public readonly CreateOrderMessageClientData $client,
        #[MapName('orderProducts')]
        /** @var Collection<CreateOrderMessageProductData> $products */
        public readonly Collection $products,
        public readonly CreateOrderMessageServiceData|Optional $service,
        #[MapName('promotion_external_id')]
        public readonly ?string $promotionExternalId = null,
        #[MapName('sber_id')]
        public readonly ?string $sberId = null,
    ) {
    }
}
