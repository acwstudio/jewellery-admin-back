<?php

namespace App\Http\Controllers\Admin\Catalog\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Product\ProductUpdateRequest;
use App\Http\Resources\Catalog\Product\ProductCollection;
use App\Http\Resources\Catalog\Product\ProductResource;
use Domain\Catalog\Models\Product;
use Domain\Catalog\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        public ProductService $productService
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $data = $request->all();

//        dump(microtime(true)-LARAVEL_START);
        $items = $this->productService->index($data);

//        $response_time = (microtime(true) - LARAVEL_START)*1000;
//        dump(microtime(true)-LARAVEL_START);
//        dd(LARAVEL_START);

        return (new ProductCollection($items))->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->all();
        /** @var Product $blogPost */
        $model = $this->productService->store($data);

        return (new ProductResource(Product::find($model->id)))
            ->response()
            ->header('Location', route('products.show', [
                'id' => $model->id
            ]));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $data = $request->all();
        data_set($data, 'id', $id);
        $model = $this->productService->show($id, $data);

        return (new ProductResource($model))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->all();
        data_set($data, 'id', $id);

        $this->productService->update($data);

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->productService->destroy($id);

        return response()->json(null, 204);
    }
}
