<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    protected $fillable = [
        'name',
        'path',
        'path_2',
    ];

    public function getPathAttribute($value)
    {
        return asset('storage/' . $value);
    }


    public function getPath2Attribute($value)
    {
        return asset('storage/' . $value);
    }


}
