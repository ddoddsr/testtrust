<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Division extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
