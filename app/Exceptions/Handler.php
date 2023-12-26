<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Psr\Log\LoggerInterface;
use Throwable;
use App\Packages\Exceptions\DomainException;

#[Schema(
    schema: 'error',
    description: 'Error response',
    properties: [
        new Property(property: 'error', properties: [
            new Property(property: 'message', type: 'string', example: 'Неизвестная ошибка'),
            new Property(property: 'code', type: 'string', example: 'unknown_error')
        ], type: 'object'),
    ]
)]
class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e)
    {
        $domainException = $this->wrapWithDomainException($e);
        $this->log($domainException);
        return $domainException->toJsonResponse();
    }

    private function log(DomainException $e): void
    {
        $sourceException = $e->getSourceException();

        App::make(LoggerInterface::class)->error(
            get_class($sourceException),
            [
                'message' => $e->getMessage(),
                'error_id' => $e->getErrorId(),
                'file' => $this->getFile($sourceException),
            ]
        );
    }

    private function wrapWithDomainException(Throwable $exception): DomainException
    {
        return DomainException::fromAny($exception);
    }

    private function getFile(\Throwable $e): string
    {
        $file = $e->getFile();
        $line = $e->getLine();

        return "$file:$line";
    }
}
