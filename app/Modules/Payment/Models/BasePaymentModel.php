<?php

declare(strict_types=1);

namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use InvalidArgumentException;

/**
 * @property-read ApplePayPayment|SamsungPayPayment|GooglePayPayment|SberbankPayment|null $basePayment
*/
abstract class BasePaymentModel extends Model
{
    use HasFactory;

    /**
     * Массив: параметр Сбербанка => поле в БД
     *
     * @var array
     */
    protected array $acquiringParamsMap = [];

    /**
     * Базовая модель платежа
     *
     * @return MorphOne
     */
    public function basePayment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'payment');
    }

    /**
     * Заполнение атрибутов, используя массив параметров, отправляемых в Сбербанк
     *
     * @param array $sberbankParams
     *
     * @return self
     */
    public function fillWithSberbankParams(array $sberbankParams): self
    {
        $attributes = [];

        foreach ($sberbankParams as $param => $value) {
            if (!isset($this->acquiringParamsMap[$param])) {
                throw new InvalidArgumentException("Param $param not found in \$acquiringParamsMap");
            }
            $attribute = $this->acquiringParamsMap[$param];
            $attributes[$attribute] = $value;
        }

        return $this->fill($attributes);
    }
}
