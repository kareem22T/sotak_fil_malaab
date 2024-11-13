<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($application) {
            if (is_null($application->admin_id) && Auth::guard('admin')->check()) {
                $application->admin_id = Auth::id();
            }
        });
    }

    protected $casts = [
        'languages' => 'array',
    ];

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

    public function ratesForVideo($video)
    {
        return $this->rates()->where('video', $video);
    }

    // Gender options
    private static $genders = [
        1 => 'Male',
        2 => 'Female',
    ];

    // Governorate options
    private static $governorates = [
        1 => 'Cairo',
        2 => 'Giza',
        3 => 'Alexandria',
        4 => 'Suez',
        5 => 'Port Said',
        6 => 'Sharm El-Sheikh',
        7 => 'El Mahalla El Kubra',
        8 => 'Damietta',
        9 => 'Ismailia',
        10 => 'Menoufia',
        11 => 'Gharbia',
        12 => 'Sharqia',
        13 => 'Dakahlia',
        14 => 'Qalyubia',
        15 => 'Beni Suef',
        16 => 'Fayoum',
        17 => 'Luxor',
        18 => 'Aswan',
        19 => 'Minya',
        20 => 'Sohag',
        21 => 'Qena',
        22 => 'Assiut',
        23 => 'New Valley',
        24 => 'Matrouh',
        25 => 'Beheira',
        26 => 'Kafr El Sheikh',
        27 => 'Suez',
        28 => 'South Sinai',
        29 => 'North Sinai',
    ];

    public function getGenderNameAttribute()
    {
        return self::$genders[$this->gender] ?? 'Unknown';
    }

    public function getGovernorateNameAttribute()
    {
        return self::$governorates[$this->governoment] ?? 'Unknown';
    }
}
