<?php

declare(strict_types=1);

namespace App\Packages\Traits;

use App\Packages\ModuleClients\OpenSearchModuleClientInterface;
use Illuminate\Support\Facades\App;

trait HasOpenSearchIndex
{
    protected string $index;

    protected static function boot()
    {
        parent::boot();

        static::bindCreated();
        static::bindUpdated();
        static::bindDeleted();
    }

    public function getIndex(): string
    {
        return $this->index;
    }

    private static function bindCreated(): void
    {
        static::created(function ($model) {
            /** @var OpenSearchModuleClientInterface $client */
            $client = App::make(OpenSearchModuleClientInterface::class);
            $client->store($model);
        });

    }

    private static function bindUpdated(): void
    {
        static::updated(function ($model) {
            /** @var OpenSearchModuleClientInterface $client */
            $client = App::make(OpenSearchModuleClientInterface::class);
            $client->update($model);
        });
    }

    private static function bindDeleted(): void
    {
        static::deleted(function ($model) {
            /** @var OpenSearchModuleClientInterface $client */
            $client = App::make(OpenSearchModuleClientInterface::class);
            $client->delete($model);
        });
    }
}
