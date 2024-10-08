<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'description',
        'start_date',
        'end_date',
    ];


    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function resourceAssignments()
    {
        return $this->hasMany(ResourceAssignment::class);
    }
}
