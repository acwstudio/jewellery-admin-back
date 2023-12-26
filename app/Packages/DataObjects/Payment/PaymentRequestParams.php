<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Payment;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

#[Schema(schema: 'payment_request_params', type: 'object')]
class PaymentRequestParams extends Data
{
    public function __construct(
        #[Property('order_number', type: 'string')]
        private readonly string $order_number,
        #[Property('amount', type: 'integer')]
        private readonly int $amount,
        #[Property('currency', type: 'integer')]
        private readonly int $currency,
        #[Property('return_url', type: 'string')]
        private readonly string $return_url,
        #[Property('fail_url', type: 'string')]
        private readonly string $fail_url,
        #[Property('description', type: 'string')]
        private readonly string $description,
        #[Property('client_id', type: 'string')]
        private readonly string $client_id,
        #[Property('features', type: 'string')]
        private readonly string $features,
        #[Property('bank_form_url', type: 'string')]
        private readonly string $bank_form_url,
        #[Property('language', type: 'string')]
        private readonly string $language = 'RU',
        #[Property('page_view', type: 'string')]
        private readonly string $page_view = '',
        #[Property('json_params', type: 'string')]
        private readonly string $json_params = '{}',
        #[Property('expiration_date', type: 'string')]
        private readonly ?string $expiration_date = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'order_number'         => $this->order_number,
            'amount'               => $this->amount,
            'currency'             => $this->currency,
            'return_url'           => $this->return_url,
            'fail_url'             => $this->fail_url,
            'description'          => $this->description,
            'client_id'            => $this->client_id,
            'language'             => $this->language,
            'page_view'            => $this->page_view,
            'json_params'          => $this->json_params,
            'expiration_date'      => $this->expiration_date,
            'features'             => $this->features,
            'bank_form_url'        => $this->bank_form_url,
        ];
    }
}
