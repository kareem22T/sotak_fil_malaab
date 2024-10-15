<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'dob',
        'gender',
        'phone',
        'email',
        'governoment',
        'educational_qualification',
        'languages',
        'accept_terms',
        'video',
        'admin_rate',
    ];

    // The languages field will be automatically cast to an array
    protected $casts = [
        'languages' => 'array',
    ];

    public function getVideoAttribute($value)
    {
        return asset('storage/' . $value);
    }
    /**
     * Get the user that owns the application.
     */
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
