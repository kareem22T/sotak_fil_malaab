<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    protected $fillable = [
        'title',
        'title_en',
        'name',
        'sub_title',
        'sub_title_en',
        'thumbnail',
        'description',
        'description_en',
        'video',
    ];

}
