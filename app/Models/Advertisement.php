<?php

namespace App\Models;

use IbrahimBougaoua\FilamentSortOrder\Traits\SortOrder;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use SortOrder;

    protected $fillable = [
        "image",
        "sort_order",
        'link'
    ];

    protected static function boot()

    {

        parent::boot();


        static::creating(function ($sponsor) {

            if (is_null($sponsor->sort_order)) {

                // This will be set after the model is saved

                $sponsor->sort_order = null; // Initialize to null

            }

        });


        static::created(function ($sponsor) {

            // After the sponsor is created, set sort_order to id

            if (is_null($sponsor->sort_order)) {

                $sponsor->sort_order = $sponsor->id;

                $sponsor->save();

            }

        });

    }
}
