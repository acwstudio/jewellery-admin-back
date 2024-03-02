<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\ImageStorage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\ImageStorage\ImageStorageStoreRequest;
use Domain\Shared\Services\ImageStorage\ImageStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageStorageController extends Controller
{
    public function __construct(public ImageStorageService $imageStorageService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImageStorageStoreRequest $request)
    {
        $data = $request->all();
        $this->imageStorageService->store($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
    }
}
