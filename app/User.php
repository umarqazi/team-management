<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function projects()
    {
        return $this->belongsToMany('App\Project');
    }

    // Task Relation Established By Umar Farooq

    public function tasks()
    {
        return $this->belongsToMany('App\Task');
    }

//    public function subtasks()
//    {
//        return $this->hasMany('App\Subtask');
//    }

    public function hours()
    {
        return $this->hasMany('App\Hour');
    }
}
