<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Payment\Responses;

use JsonException;

class SberbankResponse
{
    private array $data;
    /**
     * Код успешной операции
     */
    public const CODE_SUCCESS = 0;

    /**
     * Неизвестная ошибка
     */
    public const UNKNOWN_ERROR_MESSAGE = 'Unknown error';

    private SberbankResponse $response;

    /**
     * Response constructor.
     *
     * @param  mixed $jsonResponse
     *
     * @throws \JsonException
     */
    public function __construct(
        private readonly mixed $jsonResponse
    ) {
        $this->response = $this->setResponse($this->jsonResponse);
    }

    /**
     * @throws \JsonException
     */
    private function setResponse($response): static
    {
        if (is_string($response)) {
            $this->getResponseFromJson($response);
        }
        if (is_array($response)) {
            $this->data = $response;
        }
        return $this;
    }

    /**
     * Тело ответа сервера
     *
     * @return string
     */
    public function getJsonResponse(): string
    {
        return $this->jsonResponse ?? "";
    }

    public function getResponse(): SberbankResponse
    {
        return $this->response;
    }

    /**
     * Тело ответа сервера
     *
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * Тело ответа сервера в виде массива без полей статуса
     *
     * @return array
     */
    public function getFormattedData(): array
    {
        $response = $this->data;

        unset(
            $response['errorCode'],
            $response['errorMessage'],
            $response['error'],
            $response['success'],
        );

        return $response;
    }

    /**
     * Тело ответа сервера в виде массива
     *
     * @param $response
     *
     * @return SberbankResponse
     * @throws \JsonException
     */
    private function getResponseFromJson($response): SberbankResponse
    {
        if (!is_string($response)) {
            throw new JsonException("Cannot convert response to JSON. Response given: $response");
        }

        $response = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        $jsonError = json_last_error();

        if ($jsonError !== JSON_ERROR_NONE || null === $response) {
            throw new JsonException("Cannot convert response to JSON. Response given: $response");
        }

        $this->data = $response;

        return $this;
    }

    /**
     * Возвращает флаг успешного выполнения операции
     *
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->getErrorCode() === self::CODE_SUCCESS;
    }

    /**
     * Код ошибки
     *
     * @return int
     */
    public function getErrorCode(): int
    {
        $responseData = $this->getData();

        if (isset($responseData['errorCode'])) {
            return (int)$responseData['errorCode'];
        }

        if (isset($responseData['error']['code'])) {
            return (int)$responseData['error']['code'];
        }

        return self::CODE_SUCCESS;
    }

    /**
     * Сообщение об ошибке
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        $response = $this->getData();

        if (isset($response['errorMessage'])) {
            return (string)$response['errorMessage'];
        }

        if (isset($response['error']['message'])) {
            return (string)$response['error']['message'];
        }

        return self::UNKNOWN_ERROR_MESSAGE;
    }
}
