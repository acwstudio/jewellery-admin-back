<?php

declare(strict_types=1);

namespace App\Modules\Storage\Services;

use App\Modules\Storage\Models\File;
use App\Modules\Storage\Repositories\FileRepository;
use App\Packages\Exceptions\Storage\FileNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    public function __construct(
        private readonly FileRepository $fileRepository
    ) {
    }

    /**
     * @throws FileNotFoundException
     */
    public function getFile(int $id): File
    {
        $file = $this->fileRepository->getById($id);

        if (!$file instanceof File) {
            throw new FileNotFoundException();
        }

        return $file;
    }

    public function getFiles(array $ids): Collection
    {
        return $this->fileRepository->getByIds($ids);
    }

    public function createFile(UploadedFile $file): File
    {
        return $this->fileRepository->create($file);
    }

    /**
     * @throws FileNotFoundException
     */
    public function deleteFile(int $id): bool
    {
        $file = $this->fileRepository->getById($id);

        if (!$file instanceof File) {
            throw new FileNotFoundException();
        }

        return $this->fileRepository->delete($file);
    }
}
