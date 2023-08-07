<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceHours extends Model
{
    
    protected $fillable = [
        'user_id', 'hours', 'supervisor_id', 'department_id', 'direct_report_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'direct_report_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id' );
    }
    
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id' );
    }
}
