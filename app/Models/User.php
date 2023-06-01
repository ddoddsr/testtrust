<?php

namespace App\Models;

use App\Events\UserDeleting;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Wallo\FilamentCompanies\HasCompanies;
use Filament\Models\Contracts\FilamentUser;
use Wallo\FilamentCompanies\HasProfilePhoto;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Wallo\FilamentCompanies\HasConnectedAccounts;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Wallo\FilamentCompanies\SetsProfilePhotoFromUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasCompanies;
    use HasConnectedAccounts;
    use Notifiable;
    use SetsProfilePhotoFromUrl;
    use TwoFactorAuthenticatable;

    public function canAccessFilament(): bool
    {
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->profile_photo_url;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
        'profile_photo_path',
        'supervisor_id', 'designation', 'active',
        'is_supervisor', 'section',
        'is_worship_leader',// 'isAssociateWorshipLeader',
        'is_prayerLeader', 'is_sectionLeader',
        'exit_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => 
                $attributes['first_name'] . ' ' .
                $attributes['last_name']
        );
    }
    

    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function supervising()
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }

    public static function designations() {
        return [
            'full' => 'Full Time Staff Prayer (12 prayer meetings/week), Service (24 hours/week)',
            'part' => 'Part Time Staff Prayer (6 prayer meetings/week), Service (12 hours/week)',
            'forerunner' => 'Forerunner Church Prayer Ministry* - 1+ prayer meetings/week',
            'interccessory_team' => 'Intercessory Ministry Team* - 3 prayer meetings/week',
            'prayerroom' => 'Prayer Room Staff* - 6 prayer meetings/week (without committing to service hours)',
            'ihopu' => 'Student Staff - see IHOPU commitments',
        ];
    }
}
