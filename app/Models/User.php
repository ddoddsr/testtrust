<?php

namespace App\Models;
use Filament\Panel;
use App\Events\UserDeleting;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\HasName;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName, Auditable
{
    use HasRoles;
    use HasFactory;
    use Notifiable;
    use softDeletes;
    use HasApiTokens;
    use TwoFactorAuthenticatable;
    use \OwenIt\Auditing\Auditable;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->can('access dash') ;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return ( $this->avatar_url ) ? Storage::url($this->avatar_url) : null ;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
        'is_admin',
        'profile_photo_path', 'avatar_url','supervisor',
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
    
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => 
                $attributes['first_name'] . ' ' .
                $attributes['last_name']
        );
    }protected function nameAndEmail(): Attribute
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

    /**
     * The user's serviceHour .
     */
    public function serviceHours(): HasMany
    {
        // return $this->hasManyThrough(User::class, ServiceHours::class, 'direct_report_id'); 
        return $this->hasMany(ServiceHours::class, 'direct_report_id'); 
        
    }
     /**
     * The users that belong to the serviceHour .
     */
    public function directReports(): HasMany // BelongsToMany
    {
        // return $this->belongsToMany(DirectReport::class, 'service_hours', 'direct_report_id', 'supervisor_id'); 
        return $this->hasMany(ServiceHours::class,  'supervisor_id'); 
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
    public function getProfilePhotoUrlAttribute(){
        return $this->avatar_url;
    }

}
