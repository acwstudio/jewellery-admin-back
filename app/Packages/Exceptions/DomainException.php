<?php

declare(strict_types=1);

namespace App\Packages\Exceptions;

use App\Exceptions\DomainException as Exception;
use App\Exceptions\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DomainException extends Exception
{
    protected $description = 'Неизвестная ошибка';
    private \Throwable $sourceException;
    private string $errorId;

    public static function fromAny(\Throwable $exception): self
    {
        $self = new self(
            $exception->getMessage(),
        );

        if ($exception instanceof Exception) {
            $self->description = $exception->getDescription();
            $self->code = $exception->getCode();
            $self->errorData = $exception->getErrorData();
        }

        $self->sourceException = $exception;
        $self->errorId = (string) Str::uuid();

        return $self;
    }

    public function toJsonResponse(): JsonResponse
    {
        return new JsonResponse(
            [
                'error' => [
                    'message' => $this->getDescription(),
                    'code' => $this->getCode(),
                    'error_id' => $this->errorId,
                    'error_data' => $this->getErrorData()
                ]
            ],
            $this->getStatusCode()
        );
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getErrorId(): string
    {
        return $this->errorId;
    }

    public function getSourceException(): \Throwable
    {
        return $this->sourceException;
    }

    private function getStatusCode(): int
    {
        return match (true) {
            $this->sourceException instanceof ValidationException => 400,
            $this->sourceException instanceof AccessDeniedHttpException => 403,
            $this->sourceException instanceof NotFoundHttpException => 404,
            default => 500,
        };
    }
}
