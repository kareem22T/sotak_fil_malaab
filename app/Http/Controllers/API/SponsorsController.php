<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\Request;

class SponsorsController extends Controller
{
    public function getAll() {
        $Sponsors = Sponsor::select('image', 'link')->orderBy('sort', 'asc')->get();

        foreach ($Sponsors as $key => $value) {
            $value->image = asset('storage/' . $value->image);
        }

        return response()->json([
            'status' => true,
            'msg' => 'Sponsors fetched successfully',
            'data' =>
                $Sponsors
            ,
            'notes' => ['Sponsors fetched successfully']
        ], 200);
    }
}
