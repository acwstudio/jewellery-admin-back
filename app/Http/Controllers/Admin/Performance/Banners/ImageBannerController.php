<?php

namespace App\Http\Controllers\Admin\Performance\Banners;

use App\Http\Controllers\Controller;
use Domain\Performance\Models\ImageBanner;
use Illuminate\Http\Request;

class ImageBannerController extends Controller
{
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Domain\Performance\Models\ImageBanner  $imageBanner
     * @return \Illuminate\Http\Response
     */
    public function show(ImageBanner $imageBanner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Domain\Performance\Models\ImageBanner  $imageBanner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImageBanner $imageBanner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Domain\Performance\Models\ImageBanner  $imageBanner
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageBanner $imageBanner)
    {
        //
    }
}
