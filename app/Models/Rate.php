<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = ['user_id', 'application_id', 'rate'];

    /**
     * Get the application that was rated.
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the user who gave the rating.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
