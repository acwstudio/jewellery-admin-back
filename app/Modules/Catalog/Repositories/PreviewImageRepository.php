<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\Image\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PreviewImageRepository
{
    public function getById(int $id, bool $fail = false): ?PreviewImage
    {
        if ($fail) {
            return PreviewImage::findOrFail($id);
        }

        return PreviewImage::find($id);
    }

    public function getByIds(array $ids, bool $fail = false): Collection
    {
        $previewImages = PreviewImage::query()->whereIn('id', $ids)->get();

        if ($fail && $previewImages->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $previewImages;
    }

    public function getList(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $query = PreviewImage::query();

        $products = $query->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $products->total() === 0) {
            throw new ModelNotFoundException();
        }

        return $products;
    }

    public function create(UploadedFile $image): PreviewImage
    {
        $imageInstance = Image::load($image->getPathname());

        $previewImage = new PreviewImage();
        $mediaItem = $previewImage->addMedia($image)->toMediaCollection();
        $mediaItem->setCustomProperty('width', $imageInstance->getWidth());
        $mediaItem->setCustomProperty('height', $imageInstance->getHeight());

        $previewImage->save();

        return $previewImage;
    }

    public function delete(PreviewImage $previewImage): void
    {
        $previewImage->delete();
    }
}
