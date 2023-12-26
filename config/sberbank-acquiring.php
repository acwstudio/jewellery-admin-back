<?php

declare(strict_types=1);

use App\Modules\Users\Models\User;

return [

    'test_enabled' => env('SBERBANK_TEST_ENABLED', true),
    /**
     * Авторизационные данные
     */
    'auth' => [
        'userName' => env('SBERBANK_USERNAME', 't9717026658-api'),
        'password' => env('SBERBANK_PASSWORD', 'F1l;hHmK'),
        'token' => env('SBERBANK_TOKEN', 'kqqsubruuf32qqdouppe5rlpd7'),
    ],

    /**
     * Логин продавца в платёжном шлюзе
     */
    'merchant_login' => env('SBERBANK_MERCHANT_LOGIN', ''),

    /**
     * Адрес сервера Сбербанка
     */
    'base_uri' => env('SBERBANK_URI', 'https://3dsec.sberbank.ru'),

    /**
     * Дополнительные параметры
     */
    'params' => [
        /**
         * URL для перехода в случае успешной регистрации заказа
         */
        'return_url' => env('SBERBANK_RETURN_URL', 'https://uvi.ru/checkout/success'),

        /**
         * URL для перехода в случае неуспешной регистрации заказа
         */
        'fail_url' => env('SBERBANK_FAIL_URL', 'https://uvi.ru/checkout/fail'),
    ],

    'callback_status_key' => env('SBERBANK_CALLBACK_STATUS_KEY'),

    /**
     * Настройки модели пользователя
     */
    'user' => [
        'model' => User::class,
        'table' => 'users.users',
        'primary_key' => 'user_id',
    ],

    /**
     * Названия таблиц ('ключ' => 'название')
     */
    'tables' => [
            /**
             * Базовая таблица платежей
             */
            'payments' => 'payments' ,

            /**
             * Операции по платежам
             */
            'payment_operations' => 'payment_operations',

            /**
             * Платежи напрямую через систему Сбербанка
             */
            'sberbank_payments' => 'sberbank_payments',

            /**
             * Платежи через Apple Pay
             */
            'apple_pay_payments' => 'apple_pay_payments',

            /**
             * Платежи через Samsung Pay
             */
            'samsung_pay_payments' => 'samsung_pay_payments',

            /**
             * Платежи через Google Pay
             */
            'google_pay_payments' => 'google_pay_payments',

            /**
             * Статусы платежей
             */
            'payment_statuses' => 'payment_statuses',

            /**
             * Типы операций
             */
            'payment_operation_types' => 'payment_operation_types',

            /**
             * Типы платежных систем
             */
            'payment_systems' => 'payment_systems',
    ],
];
