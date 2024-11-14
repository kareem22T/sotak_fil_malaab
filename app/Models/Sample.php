<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    protected $fillable = [
        'title',
        'name',
        'sub_title',
        'thumbnail',
        'description',
        'video',
    ];

}
