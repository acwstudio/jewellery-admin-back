<?php

declare(strict_types=1);

namespace App\Http\Controllers\Collections;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Collections\File\CreateFileData;
use App\Packages\DataObjects\Collections\File\FileData;
use App\Packages\DataObjects\Collections\File\FileListData;
use App\Packages\DataObjects\Collections\File\GetListFileData;
use App\Packages\ModuleClients\CollectionModuleClientInterface;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class FileController extends Controller
{
    public function __construct(
        protected readonly CollectionModuleClientInterface $collectionModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/collections/file',
        summary: 'Получить список файлов коллекций',
        tags: ['Collections'],
        parameters: [
            new QueryParameter(
                name: 'pagination',
                description: 'Пагинация',
                required: false,
                schema: new Schema(ref: '#/components/schemas/pagination_data', type: 'object'),
                style: 'deepObject',
                explode: true
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Список изображений коллекций',
                content: new JsonContent(ref: '#/components/schemas/collections_file_list_data')
            )
        ]
    )]
    public function getList(GetListFileData $data): FileListData
    {
        return $this->collectionModuleClient->getFiles($data);
    }

    #[Post(
        path: '/api/v1/collections/file',
        summary: 'Загрузить файл коллекции',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/collections_create_file_data')
        ),
        tags: ['Collections'],
        responses: [
            new Response(
                response: 200,
                description: 'Файл коллекции',
                content: new JsonContent(ref: '#/components/schemas/catalog_preview_image_data')
            )
        ]
    )]
    public function create(CreateFileData $data): FileData
    {
        return $this->collectionModuleClient->createFile($data);
    }

    #[Delete(
        path: '/api/v1/collections/file/{id}',
        summary: 'Удалить файл коллекции по ID',
        tags: ['Collections'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function delete(int $id): \Illuminate\Http\Response
    {
        $this->collectionModuleClient->deleteFile($id);
        return \response('');
    }
}
