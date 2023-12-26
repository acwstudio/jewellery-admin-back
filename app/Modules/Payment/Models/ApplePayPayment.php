<?php

declare(strict_types=1);

namespace App\Modules\Payment\Models;

use App\Modules\Payment\Traits\HasPaymentToken;
use Database\Factories\Modules\Payment\ApplePayPaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $order_number
 * @property int    $amount
 * @property int    $currency
 * @property string $return_url
 * @property string $fail_url
 * @property string $description
 * @property string $client_id
 * @property string $language
 * @property string $page_view
 * @property array  $json_params
 * @property string $session_timeout_secs
 * @property string $expiration_date
 * @property string $features
 * @property string $bank_form_url
 * @property array  $acquiringParamsMap
 */
class ApplePayPayment extends BasePaymentModel
{
    use HasFactory;
    use HasPaymentToken;


    protected $table = 'payments.apple_pay_payments';

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
    ];

    protected $casts = [
        'additional_parameters' => 'array',
    ];

    protected array $acquiringParamsMap = [
        'orderNumber'          => 'order_number',
        'description'          => 'description',
        'language'             => 'language',
        'additionalParameters' => 'additional_parameters',
        'preAuth'              => 'pre_auth',
    ];

    protected $attributes = [

    ];

    protected static function newFactory()
    {
        return app(ApplePayPaymentFactory::class);
    }
}
