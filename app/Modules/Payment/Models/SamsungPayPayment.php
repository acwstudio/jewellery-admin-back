<?php

declare(strict_types=1);

namespace App\Modules\Payment\Models;

use App\Modules\Payment\Traits\HasPaymentToken;
use Database\Factories\Modules\Payment\SamsungPayPaymentFactory;

/**
 * @property string $order_number
 * @property int $amount
 * @property int $currency
 * @property string $return_url
 * @property string $fail_url
 * @property string $description
 * @property string $client_id
 * @property string $language
 * @property string $page_view
 * @property array $json_params
 * @property string $session_timeout_secs
 * @property string $expiration_date
 * @property string $features
 * @property string $bank_form_url
 * @property array $acquiringParamsMap
 */

class SamsungPayPayment extends BasePaymentModel
{
    use HasPaymentToken;

    protected $table = 'payments.samsung_pay_payments';

    public $timestamps = false;

    protected $hidden = [
        'payment_token',
    ];

    protected $fillable = [
        'order_number',
        'description',
        'language',
        'additional_parameters',
        'pre_auth',
        'client_id',
        'ip',
        'currency_code',
    ];

    protected $casts = [
        'additional_parameters' => 'array',
    ];

    protected array $acquiringParamsMap = [
        'orderNumber' => 'order_number',
        'description' => 'description',
        'language' => 'language',
        'additionalParameters' => 'additional_parameters',
        'preAuth' => 'pre_auth',
        'clientId' => 'client_id',
        'ip' => 'ip',
        'currencyCode' => 'currency_code',
    ];

    protected static function newFactory()
    {
        return app(SamsungPayPaymentFactory::class);
    }
}
