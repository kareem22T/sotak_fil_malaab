<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jury;
use Illuminate\Http\Request;


class JuriesController extends Controller
{
    public function getAllJuries(Request $request)
    {
        // Determine the language from the headers
        $lang = $request->header('lang', 'ar'); // Default to 'ar' if no header is provided

        // Fetch all juries
        $juries = Jury::all();

        // Modify jury data based on the language
        foreach ($juries as $jury) {
            $jury->name = $lang === 'en' ? $jury->name_en : $jury->name;
            $jury->description = $lang === 'en' ? $jury->description_en : $jury->description;

            // Keep the image URL intact
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
