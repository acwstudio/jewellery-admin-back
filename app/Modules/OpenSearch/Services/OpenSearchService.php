<?php

declare(strict_types=1);

namespace App\Modules\OpenSearch\Services;

use App\Packages\Exceptions\OpenSearch\OpenSearchIndexExpected;
use Illuminate\Database\Eloquent\Model;
use OpenSearch\Client;

class OpenSearchService
{
    public function __construct(
        private readonly Client $client
    ) {
    }

    public function createIndex(string $indexName): void
    {
        $this->client->indices()->create([
            'index' => $indexName,
        ]);
    }

    /**
     * @throws OpenSearchIndexExpected
     */
    public function store(Model $model): void
    {
        if (!method_exists($model, 'getIndex')) {
            throw new OpenSearchIndexExpected();
        }

        if ($this->isExist($model)) {
            $this->update($model);
            return;
        }

        $this->client->create([
            'id' => $model->getKey(),
            'index' => $model->getIndex(),
            'body' => $model->toArray(),
        ]);
    }

    /**
     * @throws OpenSearchIndexExpected
     */
    public function update(Model $model): void
    {
        if (!method_exists($model, 'getIndex')) {
            throw new OpenSearchIndexExpected();
        }

        $this->client->update([
            'id' => $model->getKey(),
            'index' => $model->getIndex(),
            'body' => [
                'doc' => $model->toArray()
            ],
        ]);
    }

    /**
     * @throws OpenSearchIndexExpected
     */
    public function delete(Model $model): void
    {
        if (!method_exists($model, 'getIndex')) {
            throw new OpenSearchIndexExpected();
        }

        $this->client->delete([
            'id' => $model->getKey(),
            'index' => $model->getIndex(),
        ]);
    }

    public function isExist(Model $model): bool
    {
        if (!method_exists($model, 'getIndex')) {
            throw new OpenSearchIndexExpected();
        }

        return $this->client->exists([
            'id' => $model->getKey(),
            'index' => $model->getIndex(),
        ]);
    }

    public function search(string $index, array $body = [])
    {
        return $this->client->search([
            'index' => $index,
            'body' => $body
        ]);
    }
}
