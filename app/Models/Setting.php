<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'main_sponsor',
        'profile_ad',
        'ended_at',
        'terms_and_condition'
    ];
}
