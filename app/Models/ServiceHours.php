<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceHours extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'hours', 'supervisor_id', 'department_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
