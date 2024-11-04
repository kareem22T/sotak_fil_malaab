<?php

namespace Database\Seeders;

use App\Models\Sample;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddSamplesLayout extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Sample::count() === 0) {
            // Insert two empty samples with IDs 1 and 2
            Sample::create([
                'id' => 1,
                'name' => 'Sample 1',
                'title' => '',
                'sub_title' => '',
                'description' => '',
                'video' => ''
            ]);

            Sample::create([
                'id' => 2,
                'name' => 'Sample 2',
                'title' => '',
                'sub_title' => '',
                'description' => '',
                'video' => ''
            ]);
        }
    }
}
