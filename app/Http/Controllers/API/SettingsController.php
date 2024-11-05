<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function get() {
        $settings = Setting::select(
            'main_sponsor',
            'profile_ad',
            'about',
            'submission',
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

    public function daysLeft() {
        $settings = Setting::first();
        $daysLeft = Carbon::now()->diffInDays(Carbon::parse($settings->ended_at), false);

        return response()->json([
            'status' => true,
            'msg' => 'settings fetched successfully',
            'data' =>
                $daysLeft
            ,
            'notes' => ['settings fetched successfully']
        ], 200);
    }
}
