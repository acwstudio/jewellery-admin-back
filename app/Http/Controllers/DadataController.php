<?php

namespace App\Http\Controllers;

//DaData
use App\Exceptions\ValidationException;
use Illuminate\Http\Request;
use MoveMoveIo\DaData\Facades\DaDataAddress;

class DadataController extends Controller
{
    const COUNT_SUGGESTIONS = 5;
    const COUNT_ADDRESS_BY_COORDS = 1;

    /**
     * @param Request $request
     *
     * @throws ValidationException
     *
     * @return mixed
     */
    public function getSuggestionsByQuery(Request $request)
    {
        $this->validate($request, [
            'query' => 'required'
        ]);

        $query = $request->get('query');
        $suggestions = DaDataAddress::prompt($query, DadataController::COUNT_SUGGESTIONS);
        return $suggestions;
    }

    /**
     * @param Request $request
     *
     * @throws ValidationException
     *
     * @return mixed
     */
    public function getAddressByCoords(Request $request)
    {
        $this->validate($request, [
            'lat' => 'required',
            'lon' => 'required'
        ]);

        $lat = $request->get('lat');
        $lon = $request->get('lon');
        $address = DaDataAddress::geolocate($lat, $lon, DadataController::COUNT_ADDRESS_BY_COORDS);
        return $address;
    }

}
