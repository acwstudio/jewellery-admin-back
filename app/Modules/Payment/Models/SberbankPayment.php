<?php

declare(strict_types=1);

namespace App\Modules\Payment\Models;

use Database\Factories\Modules\Payment\SberbankPaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
class SberbankPayment extends BasePaymentModel
{
    use HasFactory;

    protected $table = 'payments.sberbank_payments';

    public $timestamps = false;

    protected $fillable = [
        'order_number',
        'amount',
        'currency',
        'return_url',
        'fail_url',
        'description',
        'client_id',
        'language',
        'page_view',
        'json_params',
        'session_timeout_secs',
        'expiration_date',
        'features',
        'bank_form_url',
    ];

    protected $casts = [
        'json_params' => 'array',
    ];

    protected array $acquiringParamsMap = [
        'order_number'         => 'order_number',
        'amount'               => 'amount',
        'currency'             => 'currency',
        'return_url'           => 'return_url',
        'fail_url'             => 'fail_url',
        'description'          => 'description',
        'language'             => 'language',
        'client_id'            => 'client_id',
        'page_view'            => 'page_view',
        'json_params'          => 'json_params',
        'session_timeout_secs' => 'session_timeout_secs',
        'expiration_date'      => 'expiration_date',
        'features'             => 'features',
        'bank_form_url'        => 'bank_form_url',
    ];

    protected static function newFactory()
    {
        return app(SberbankPaymentFactory::class);
    }
}
