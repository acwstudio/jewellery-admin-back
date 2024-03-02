<?php

namespace App\Http\Controllers\Admin\Performance\Banners;

use App\Http\Controllers\Controller;
use Domain\Performance\Models\TypeBanner;
use Illuminate\Http\Request;

class TypeBannerController extends Controller
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
     * @param  \Domain\Performance\Models\TypeBanner  $typeBanner
     * @return \Illuminate\Http\Response
     */
    public function show(TypeBanner $typeBanner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Domain\Performance\Models\TypeBanner  $typeBanner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeBanner $typeBanner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Domain\Performance\Models\TypeBanner  $typeBanner
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeBanner $typeBanner)
    {
        //
    }
}
