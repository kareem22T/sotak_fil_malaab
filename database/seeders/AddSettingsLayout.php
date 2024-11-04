<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddSettingsLayout extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Setting::count() === 0) {
            Setting::create([
                'id' => 1,
                'main_sponsor' => '',
                'profile_ad' => '',
                'terms_and_condition' => '',
            ]);
        }
    }
}
