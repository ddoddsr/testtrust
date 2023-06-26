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
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Wallo\FilamentCompanies\HasConnectedAccounts;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Wallo\FilamentCompanies\SetsProfilePhotoFromUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName
{
    use HasFactory;
    use Notifiable;
    use softDeletes;
    use HasApiTokens;
    use HasCompanies;
    use HasProfilePhoto;
    use HasConnectedAccounts;
    use SetsProfilePhotoFromUrl;
    use TwoFactorAuthenticatable;

    public function canAccessFilament(): bool
    {
        //  samplr return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
        return $this->is_admin == true;
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
        'is_admin',
        'profile_photo_path', 'supervisor',
        'supervisor_id', 'designation', 'designation_id', 'active',
        'is_supervisor', 'section',
        'is_worship_leader', 'is_associate_worship_leader',
        'is_prayerLeader', 'is_sectionLeader',
        'exit_date','effective_date', 'review',
        'department_id'
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
    
    public function emailAlias()
    {
        return $this->hasMany(EmailAlias::class);
    }
    
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => 
                $attributes['first_name'] . ' ' .
                $attributes['last_name']
        );
    }
    protected function nameAndEmail(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => 
                $attributes['first_name'] . ' ' .
                $attributes['last_name'] . ' ' .
                $attributes['email']
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

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function supervising()
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }

    public static function designations() {
        // If you change ther order, keep the keys with the line they are on
        return [
            1 => 'Full Time Staff Prayer (12 prayer meetings/week), Service (24 hours/week)',
            2 => 'Part Time Staff Prayer (6 prayer meetings/week), Service (12 hours/week)',
            3 => 'Forerunner Church Prayer Ministry* - 1+ prayer meetings/week',
            4 => 'Intercessory Ministry Team* - 3 prayer meetings/week',
            5 => 'Prayer Room Staff* - 6 prayer meetings/week (without committing to service hours)',
            6 => 'Student Staff - see IHOPU commitments',
            7 => 'Dept_9940',
        ];
    }
    public static function designations_key() {
        $short = [];
        foreach (User::designations() as $key => $value ) {
            $short[strtolower(substr($value, 0, 4 ))] = $key;
        }
        return $short;
    }
    
    public static function designations_short() {
        $short = [];
        foreach (User::designations() as $key => $value ) {
            $short[$key] = strtok($value, " ");
        }
        return $short;
    }
}
