<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Storage;

use App\Packages\Exceptions\DomainException;

class FileNotFoundException extends DomainException
{
    protected $code = 'checkout-back_storage_module_file_not_found_exception';
    protected $message = 'File not found';
    protected $description = 'Файл не найден';
}
