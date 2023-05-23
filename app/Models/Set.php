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
        'title',
    ];

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

}
