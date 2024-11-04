<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function get() {
        $settings = Setting::select(
            'main_sponsor',
            'profile_ad',
            'terms_and_condition'
        )->first();

        $settings->main_sponsor = asset('storage/' . $settings->main_sponsor);
        $settings->profile_ad = asset('storage/' . $settings->profile_ad);

        return response()->json([
            'status' => true,
            'msg' => 'settings fetched successfully',
            'data' =>
                $settings
            ,
            'notes' => ['settings fetched successfully']
        ], 200);

    }
}
