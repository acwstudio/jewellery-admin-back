<?php

declare(strict_types=1);

namespace App\Modules\Storage;

use App\Modules\Storage\Services\FileService;
use App\Packages\DataObjects\Storage\FileData;
use App\Packages\DataObjects\Storage\FileListData;
use App\Packages\DataObjects\Storage\UploadFilesData;
use App\Packages\ModuleClients\StorageModuleClientInterface;

final class StorageModuleClient implements StorageModuleClientInterface
{
    public function __construct(
        private readonly FileService $fileService
    ) {
    }

    public function getFile(int $id): FileData
    {
        return FileData::fromModel($this->fileService->getFile($id));
    }

    public function uploadFiles(UploadFilesData $data): FileListData
    {
        $fileList = [];

        foreach ($data->files as $file) {
            $fileList[] = $this->fileService->createFile($file);
        }

        return FileListData::fromArray($fileList);
    }

    public function deleteFile(int $id): bool
    {
        return $this->fileService->deleteFile($id);
    }
}
