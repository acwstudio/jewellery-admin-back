<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Repositories\PreviewImageRepository;
use App\Modules\Catalog\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PreviewImageService
{
    public function __construct(
        private readonly PreviewImageRepository $previewImageRepository
    ) {
    }

    public function getPreviewImage(int $id): ?PreviewImage
    {
        return $this->previewImageRepository->getById($id);
    }

    public function getProductImageList(Pagination $pagination): LengthAwarePaginator
    {
        return $this->previewImageRepository->getList($pagination);
    }

    public function createPreviewImage(UploadedFile $image): PreviewImage
    {
        return $this->previewImageRepository->create($image);
    }

    public function deletePreviewImage(int $id): void
    {
        $previewImage = $this->previewImageRepository->getById($id, true);
        $this->previewImageRepository->delete($previewImage);
    }
}
