<?php

declare(strict_types=1);

namespace App\Modules\Collections\Repository;

use App\Modules\Collections\Support\Pagination;
use App\Modules\Collections\Models\File;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileRepository
{
    public function getById(int $id, bool $fail = false): ?File
    {
        if ($fail) {
            return File::findOrFail($id);
        }

        return File::find($id);
    }

    /**
     * @param array $ids
     * @param bool $fail
     * @return Collection<File>
     */
    public function getByIds(array $ids, bool $fail = false): Collection
    {
        $files = File::query()->whereIn('id', $ids)->get();

        if ($fail && $files->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $files;
    }

    public function getList(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $query = File::query();

        $files = $query->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $files->total() === 0) {
            throw new ModelNotFoundException();
        }

        return $files;
    }

    public function create(UploadedFile $file): File
    {
        $fileModel = new File();
        $media = $fileModel->addMedia($file)->toMediaCollection();
        $media->setCustomProperty('mimeType', $file->getMimeType());

        $fileModel->save();

        return $fileModel;
    }

    public function delete(File $file): void
    {
        $file->delete();
    }
}
