<?php

namespace Database\Seeders;

use App\Models\Rate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class UpdateRatesWithVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rate::all()->each(function ($rate) {
            // Randomly select 'video_1' or 'video_2' for each rate entry
            $rate->video = Arr::random(['video_1', 'video_2']);
            $rate->save();
        });
    }
}
