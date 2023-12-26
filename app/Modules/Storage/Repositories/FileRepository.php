<?php

declare(strict_types=1);

namespace App\Modules\Storage\Repositories;

use App\Modules\Storage\Models\File;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileRepository
{
    public function getById(int $id): ?File
    {
        return File::find($id);
    }

    public function getByIds(array $ids): Collection
    {
        return File::query()->findMany($ids);
    }

    public function create(UploadedFile $uploadedFile): File
    {
        $file = new File();
        $file->addMedia($uploadedFile)->toMediaCollection();
        $file->save();

        return $file;
    }

    public function delete(File $file): bool
    {
        return $file->delete() ?? false;
    }
}
