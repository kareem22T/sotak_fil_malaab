<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jury;
use Illuminate\Http\Request;


class JuriesController extends Controller
{
    public function getAllJuries(Request $request)
    {

        $isEnglish = $request->header('lang') === 'en'; // Check if the header specifies English

        // Fetch samples
        $juries = Jury::select(
            $isEnglish ? 'name_en as name' : 'name',
            $isEnglish ? 'description_en as description' : 'description',
            'video',
            'thumbnail'
        )->get();


        // Modify jury data based on the language
        foreach ($juries as $jury) {
            $jury->image = asset('storage/' . $jury->image);
        }


        // Return the response
        return response()->json([
            'status' => true,
            'msg' => 'Juries fetched successfully',
            'data' => $juries,
            'notes' => ['Juries fetched successfully']
        ], 200);
    }
}
