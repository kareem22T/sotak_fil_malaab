<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'main_sponsor',
        'profile_ad',
        'terms_and_condition',
        'about_us',
        'submission',
        'ended_at',
        'interval_of_repeat',
        'repeated_ad',
    ];
}
