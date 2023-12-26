<?php

namespace App\Exceptions;

class UserNotFoundException extends DomainException
{
    protected $message = 'User not found';
    protected $code = 'checkout-back_user_not_found';
    protected $description = 'Пользователь не найден';
}
