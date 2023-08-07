<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Set extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'section_leader_id',
        'worship_leader_id',
        'prayer_leader_id',
        'associate_worship_leader_id',
        'title',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
    public function sectionLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'section_leader_id'); 
    }
    public function worshipLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worship_leader_id'); 
    }
    public function prayerLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prayer_leader_id'); 
    }

    public static function setOfDay () {
        return [
            '12am',
            '2am',
            '4am',
            '6am',
            '8am',
            '10am',
            '12pm',
            '2pm',
            '4pm',
            '6pm',
            '8pm',
            '10pm',
        ];
    }

    public static function dayOfWeek (){ 
        return ['Sunday', 'Monday',  'Tuesday',  'Wednesday',  'Thursday', 'Friday', 'Saturday'];
    }
    public static function dayOfWeekStr (){ 
        return ['Sunday' => 'Sunday', 
        'Monday' => 'Monday',  
        'Tuesday' => 'Tuesday',  
        'Wednesday' => 'Wednesday',  
        'Thursday' => 'Thursday', 
        'Friday' => 'Friday', 
        'Saturday' => 'Saturday',
    ];
    }

    public static function intercessionSets() {
        return ['12am', '4am', '6am', '10am', '4pm', '8pm'];
    }
}
