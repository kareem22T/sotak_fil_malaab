<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function getAll() {
        $Advertisements = Advertisement::select('image', 'link')->orderBy('sort', 'asc')->get();

        foreach ($Advertisements as $key => $value) {
            $value->image = asset('storage/' . $value->image);
        }

        return response()->json([
            'status' => true,
            'msg' => 'Advertisements fetched successfully',
            'data' =>
                $Advertisements
            ,
            'notes' => ['Advertisements fetched successfully']
        ], 200);
    }
}
