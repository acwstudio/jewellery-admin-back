<?php

declare(strict_types=1);

namespace Domain\Shared\Services\ImageStorage;

use Domain\Shared\Repositories\ImageStorage\ImageStorageRepository;

final class ImageStorageService
{
    public function __construct(public ImageStorageRepository $imageStorageRepository)
    {
    }

    public function index()
    {
    }

    public function store(array $data)
    {
        $this->imageStorageRepository->store($data);
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
