<?php

declare(strict_types=1);

namespace App\Modules\Payment\Traits;

use App\Packages\Exceptions\Sber\ConfigException;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;

trait HasConfig
{
    /**
     * @param string $tableNameKey
     *
     * @return string
     * @throws Exception
     */
    public function getTableName(string $tableNameKey): string
    {
        return $this->getConfigParam("tables.$tableNameKey");
    }

    /**
     * Возвращает массив параметров для авторизации
     *
     * @return array
     * @throws Exception
     */
    public function getConfigAuthParams(): array
    {
        return $this->getConfigParam('auth');
    }

    /**
     * Возвращает логин продавца
     *
     * @return string
     * @throws Exception
     */
    public function getConfigMerchantLoginParam(): string
    {
        return $this->getConfigParam('merchant_login');
    }

    /**
     * Возвращает адрес сервера Сбербанка
     *
     * @return string
     * @throws Exception
     */
    public function getConfigBaseURIParam(): string
    {
        return $this->getConfigParam('base_uri');
    }

    /**
     * @param string $key
     *
     * @return Repository|Application|mixed
     * @throws ConfigException
     */
    public function getConfigParam(string $key): mixed
    {
        $value = config("sberbank-acquiring.$key");
        if (is_null($value)) {
            throw new ConfigException(
                "Error: cannot find key \"$key\" in config/sberbank-acquiring.php. Config could not be loaded.",
            );
        }
        return $value;
    }
}
