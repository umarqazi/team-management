<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Relations Established By Umar Farooq
    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function subtasks()
    {
        return $this->hasMany('App\Subtask');
    }

    public function hours()
    {
        return $this->hasMany('App\Hour');
    }
}
