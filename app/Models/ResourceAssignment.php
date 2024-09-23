<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceAssignment extends Model
{
    protected $fillable = ['project_id', 'resource_id', 'assigned_from', 'assigned_to'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
