<?php

declare(strict_types=1);

namespace App\Modules\OpenSearch;

use App\Modules\OpenSearch\Services\OpenSearchService;
use App\Packages\Exceptions\OpenSearch\OpenSearchIndexExpected;
use App\Packages\ModuleClients\OpenSearchModuleClientInterface;
use Illuminate\Database\Eloquent\Model;

class OpenSearchModuleClient implements OpenSearchModuleClientInterface
{
    public function __construct(
        private readonly OpenSearchService $openSearchService
    ) {
    }

    public function createIndex(string $indexName): void
    {
        $this->openSearchService->createIndex($indexName);
    }

    /**
     * @throws OpenSearchIndexExpected
     */
    public function store(Model $model): void
    {
        $this->openSearchService->store($model);
    }

    /**
     * @throws OpenSearchIndexExpected
     */
    public function update(Model $model): void
    {
        $this->openSearchService->update($model);
    }

    /**
     * @throws OpenSearchIndexExpected
     */
    public function delete(Model $model): void
    {
        $this->openSearchService->delete($model);
    }
}
