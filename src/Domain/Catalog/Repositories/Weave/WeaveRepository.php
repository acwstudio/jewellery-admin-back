<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Weave;

use Domain\Catalog\Models\Weave;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class WeaveRepository implements WeaveRepositoryInterface
{

    public function index(array $data): Paginator
    {
        return QueryBuilder::for(Weave::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('weaves'))
            ->allowedIncludes(['products'])
            ->allowedFilters([
                AllowedFilter::exact('slug'),
                AllowedFilter::exact('id'),
                'name','active'
            ])
            ->allowedSorts(['name','id','slug'])
            ->paginate($data['per_page'] ?? null)
            ->appends($data);
    }

    public function store(array $data): Model|Weave
    {
        return Weave::create(data_get($data, 'data.attributes'));
    }

    public function show(int $id, array $data): Model|Weave
    {
        return QueryBuilder::for(Weave::class)
            ->where('id', $id)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('weaves'))
            ->allowedIncludes(['products'])
            ->firstOrFail();
    }

    public function update(array $data): Model|Weave
    {
        $model = Weave::findOrFail(data_get($data, 'id'));
        $model->update(data_get($data, 'data.attributes'));

        return $model;
    }

    public function destroy(int $id): void
    {
        Weave::findOrFail($id)->delete();
    }
}
