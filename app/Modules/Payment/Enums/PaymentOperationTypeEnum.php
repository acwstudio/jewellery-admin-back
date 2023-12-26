<?php

declare(strict_types=1);

namespace App\Modules\Payment\Enums;

enum PaymentOperationTypeEnum: int
{
    /**
     * Регистрация заказа
     */
    case REGISTER = 1;

    /**
     * Регистрация заказа с предавторизацией
     */
    case REGISTER_PRE_AUTH = 2;

    /**
     * Запрос завершения оплаты заказа
     */
    case DEPOSIT = 3;

    /**
     * Запрос отмены оплаты заказа
     */
    case REVERSE = 4;

    /**
     * Запрос возврата средств оплаты заказа
     */
    case REFUND = 5;

    /**
     * Расширенный запрос состояния заказа
     */
    case GET_EXTENDED_STATUS = 6;

    /**
     * Запрос оплаты через Apple Pay
     */
    case APPLE_PAY_PAYMENT = 7;

    /**
     * Запрос оплаты через Samsung Pay
     */
    case SAMSUNG_PAY_PAYMENT = 8;

    /**
     * Запрос оплаты через Google Pay
     */
    case GOOGLE_PAY_PAYMENT = 9;

    /**
     * Запрос сведений о кассовом чеке
     */
    case GET_RECEIPT_STATUS = 10;

    /**
     * Запрос активации связки
     */
    case BIND_REQUEST = 11;

    /**
     * Запрос деактивации связки
     */
    case UNBIND_REQUEST = 12;

    /**
     * Запрос списка всех связок клиента
     */
    case GET_BINDINGS_BY_CLIENT = 13;

    /**
     * Запрос списка связок определённой банковской карты
     */
    case GET_BINDINGS_BY_CARD = 14;

    /**
     * Запрос изменения срока действия связки
     */
    case EXTEND_BINDING = 15;

    /**
     * Запрос проверки вовлечённости карты в 3DS
     */
    case VERIFY_ENROLLMENT = 16;
}
