<?php

declare(strict_types=1);

namespace App\Modules\Collections\Services;

use App\Modules\Collections\Models\File;
use App\Modules\Collections\Repository\FileRepository;
use App\Modules\Collections\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    public function __construct(
        private readonly FileRepository $fileRepository
    ) {
    }

    public function getFile(int $id): ?File
    {
        return $this->fileRepository->getById($id);
    }

    public function getFiles(Pagination $pagination): LengthAwarePaginator
    {
        return $this->fileRepository->getList($pagination);
    }

    public function createFile(UploadedFile $image): File
    {
        return $this->fileRepository->create($image);
    }

    public function deleteFile(int $id): void
    {
        $file = $this->fileRepository->getById($id, true);
        $this->fileRepository->delete($file);
    }
}
