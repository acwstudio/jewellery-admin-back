<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Packages\DataObjects\Storage\FileData;
use App\Packages\DataObjects\Storage\FileListData;
use App\Packages\DataObjects\Storage\UploadFilesData;

interface StorageModuleClientInterface
{
    public function getFile(int $id): FileData;

    public function uploadFiles(UploadFilesData $data): FileListData;

    public function deleteFile(int $id): bool;
}
