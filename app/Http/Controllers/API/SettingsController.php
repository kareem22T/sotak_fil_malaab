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
            'terms_and_condition',
            'about_us',
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
