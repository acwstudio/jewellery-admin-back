<?php

declare(strict_types=1);

namespace App\Modules\Collections\Repository;

use App\Modules\Collections\Contracts\Pipelines\StoneQueryBuilderPipelineContract;
use App\Modules\Collections\Models\Stone;
use App\Modules\Collections\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StoneRepository
{
    public function getById(int $id, bool $fail = false): ?Stone
    {
        if ($fail) {
            return Stone::findOrFail($id);
        }

        return Stone::find($id);
    }

    /**
     * @param array $ids
     * @param bool $fail
     * @return Collection<Stone>
     */
    public function getByIds(array $ids, bool $fail = false): Collection
    {
        $stones = Stone::query()->whereIn('id', $ids)->get();

        if ($fail && $stones->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $stones;
    }

    public function getList(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $query = Stone::query();

        /** @var StoneQueryBuilderPipelineContract $pipeline */
        $pipeline = app(StoneQueryBuilderPipelineContract::class);

        /** @var LengthAwarePaginator $stones */
        $stones = $pipeline
            ->send($query)
            ->thenReturn()
            ->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $stones->total() === 0) {
            throw new ModelNotFoundException();
        }

        return $stones;
    }

    public function create(string $name): Stone
    {
        $stone = new Stone([
            'name' => $name
        ]);

        $stone->save();

        return $stone;
    }

    public function delete(Stone $stone): void
    {
        $stone->delete();
    }
}
