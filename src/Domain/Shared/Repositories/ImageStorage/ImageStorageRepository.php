<?php

declare(strict_types=1);

namespace Domain\Shared\Repositories\ImageStorage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class ImageStorageRepository
{
    public function index()
    {
    }

    public function store(array $data)
    {
        /** @var UploadedFile $file */
        $file = $data['image'];

        $model = app(data_get($data, 'model_type'));

        $metadata = $model::find(data_get($data, 'metadata_id'));

        $dir = strtolower(str_replace('\\', '-', data_get($data, 'model_type')));
        $dirExplode = (explode('-', $dir));
        $ext = $file->extension();
        $name = end($dirExplode) . '-' . data_get($data, 'metadata_id') . '.' . $ext;

////        Storage::delete(Storage::allFiles('public/' . $dir));
        $imageLink = $file->storeAs($dir, $name);
        $metadata->update([
            'extension'  => $ext,
            'image_link' => $imageLink,
            'name'       => $name,
            'slug'       => Str::slug($name),
            'size'       => $file->getSize(),
            'mime_type'  => $file->getMimeType(),
//            'sequence'   => $data['sequence'],
        ]);
////        dump(Storage::lastModified("public/domain-performance-models-imagebanner/image-199.jpg"));
    }

    public function show()
    {
    }

    public function update()
    {
    }

    public function destroy()
    {
    }
}
