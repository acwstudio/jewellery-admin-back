<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Collections\Services;

use App\Modules\Collections\Models\File;
use App\Modules\Collections\Services\FileService;
use App\Modules\Collections\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileServiceTest extends TestCase
{
    private FileService $fileService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fileService = app(FileService::class);
        Storage::fake('local');
    }

    public function testSuccessfulGet()
    {
        $file = File::factory()->create();

        $results = $this->fileService->getFile($file->getKey());

        self::assertInstanceOf(File::class, $results);
    }

    public function testSuccessfulGetList()
    {
        File::factory(5)->create();

        $pagination = new Pagination(1, 4);
        $results = $this->fileService->getFiles($pagination);

        self::assertInstanceOf(LengthAwarePaginator::class, $results);
        self::assertCount(4, $results->items());
    }

    public function testSuccessfulCreate()
    {
        $file = UploadedFile::fake()->image('test.png');

        $results = $this->fileService->createFile($file);

        self::assertInstanceOf(File::class, $results);
    }

    public function testSuccessfulDelete()
    {
        /** @var File $file */
        $file = File::factory()->create();

        $this->fileService->deleteFile($file->getKey());

        self::assertModelMissing($file);
    }
}
