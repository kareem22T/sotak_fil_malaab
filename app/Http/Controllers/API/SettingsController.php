<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function get(Request $request) {
        $isEnglish = $request->header('lang') === 'en'; // Check if the header specifies English

        // Fetch samples
        $settings = Setting::select(
            $isEnglish ? 'terms_and_condition_en as terms_and_condition' : 'terms_and_condition',
            $isEnglish ? 'about_us_en as about_us' : 'about_us',
            'main_sponsor',
            'profile_ad',
            'submission',
            'ended_at',
        )->first();

        $settings->main_sponsor = asset('storage/' . $settings->main_sponsor);
        $settings->profile_ad = asset('storage/' . $settings->profile_ad);
        if ($settings && $settings->ended_at) {
            $settings->ended_at = Carbon::parse($settings->ended_at)->toDateString();
        }
        return response()->json([
            'status' => true,
            'msg' => 'settings fetched successfully',
            'data' =>
                $settings
            ,
            'notes' => ['settings fetched successfully']
        ], 200);

    }

    public function daysLeft() {
        $settings = Setting::first();
        $daysLeft = Carbon::now()->diffInDays(Carbon::parse($settings->ended_at), false);

        return response()->json([
            'status' => true,
            'msg' => 'settings fetched successfully',
            'data' => round($daysLeft),
            'notes' => ['settings fetched successfully']
        ], 200);
    }

}
