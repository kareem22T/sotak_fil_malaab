<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jury;
use Illuminate\Http\Request;

class JuriesController extends Controller
{
    public function getAllJuries() {
        $juries = Jury::all();

        foreach ($juries as $jury) {
            $jury->image = asset('storage/' . $jury->image);
        }

        return response()->json([
            'status' => true,
            'msg' => 'Juries fetched successfully',
            'data' =>
                $juries
            ,
            'notes' => ['Juries fetched successfully']
        ], 200);
    }
}
