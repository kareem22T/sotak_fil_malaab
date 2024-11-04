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
        'video_1',
        'video_2',
        'admin_rate',
        'admin_id',
        'is_approved',
    ];

    // The languages field will be automatically cast to an array
    protected $casts = [
        'languages' => 'array',
    ];

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

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }


}
