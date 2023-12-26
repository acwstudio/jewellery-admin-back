<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\DataObjects\Storage\FileData;
use App\Packages\DataObjects\Storage\FileListData;
use App\Packages\DataObjects\Storage\UploadFilesData;
use App\Packages\ModuleClients\StorageModuleClientInterface;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class FileController extends Controller
{
    public function __construct(
        private readonly StorageModuleClientInterface $storageModuleClient,
    ) {
    }

    #[Get(
        path: '/api/v1/storage/file/{id}',
        description: 'Return a file',
        summary: 'Получить файл по ID',
        tags: ['Storage'],
        parameters: [
            new PathParameter(
                name: 'id',
                description: 'File ID',
                required: true,
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Success response',
                content: new JsonContent(ref: '#/components/schemas/storage_file_data')
            ),
            new Response(
                response: 500,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            ),
            new Response(
                response: 404,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            )
        ],
    )]
    public function get(int $id): FileData
    {
        return $this->storageModuleClient->getFile($id);
    }

    #[Post(
        path: '/api/v1/storage/file/upload',
        summary: 'Загрузить файлы',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/storage_upload_files')
        ),
        tags: ['Storage'],
        responses: [
            new Response(
                response: 200,
                description: 'Success response',
                content: new JsonContent(ref: '#/components/schemas/storage_file_list_data')
            ),
            new Response(
                response: 500,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            ),
            new Response(
                response: 404,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            )
        ],
    )]
    public function upload(UploadFilesData $data): FileListData
    {
        return $this->storageModuleClient->uploadFiles($data);
    }

    #[Delete(
        path: '/api/v1/storage/file/{id}',
        summary: 'Удалить файл по ID',
        tags: ['Storage'],
        parameters: [
            new PathParameter(
                name: 'id',
                description: 'File ID',
                required: true,
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Success response',
                content: new JsonContent(ref: '#/components/schemas/success_data')
            ),
            new Response(
                response: 500,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            ),
            new Response(
                response: 404,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            )
        ],
    )]
    public function delete(int $id): SuccessData
    {
        return new SuccessData(
            $this->storageModuleClient->deleteFile($id)
        );
    }
}
