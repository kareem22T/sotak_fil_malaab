<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'main_sponsor',
        'profile_ad',
        'terms_and_condition',
        'terms_and_condition_en',
        'about_us',
        'about_us_en',
        'submission',
        'repeated_ad',
        'ended_at',
        'interval_of_repeat',
        'registration_terms_and_conditions',
        'registration_terms_and_conditions_en',
    ];
}
