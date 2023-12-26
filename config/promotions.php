<?php

declare(strict_types=1);

use App\Modules\Promotions\Support\DataNormalizer\RabbitMQ\PromotionDataNormalizer;

return [
    'promocode_condition_rules' => [
        \App\Modules\Promotions\Modules\Promocodes\Support\Rule\TotalAmountPromocodeRule::class,
        \App\Modules\Promotions\Modules\Promocodes\Support\Rule\PhoneNumberPromocodeRule::class
    ],
    'promocode_condition_validators' => [
        \App\Modules\Promotions\Modules\Promocodes\Support\Validator\StartAtPromocodeValidator::class,
        \App\Modules\Promotions\Modules\Promocodes\Support\Validator\FinishAtPromocodeValidator::class,
        \App\Modules\Promotions\Modules\Promocodes\Support\Validator\IsActivePromocodeValidator::class,
        \App\Modules\Promotions\Modules\Promocodes\Support\Validator\OneUsePerUserPromocodeValidator::class,
        \App\Modules\Promotions\Modules\Promocodes\Support\Validator\RulePromocodeValidator::class
    ],
    'promocode_benefit_activators' => [
        \App\Modules\Promotions\Modules\Promocodes\Support\Benefit\PercentAmountPromocodeBenefitActivator::class,
        \App\Modules\Promotions\Modules\Promocodes\Support\Benefit\NominalAmountPromocodeBenefitActivator::class,
        \App\Modules\Promotions\Modules\Promocodes\Support\Benefit\IsFreeDeliveryPromocodeBenefitActivator::class,
    ],

    'import' => [
        'promotion' => [
            'data_normalizer' => PromotionDataNormalizer::class,
        ],
    ]
];
