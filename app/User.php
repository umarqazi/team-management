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
    /*public function tasks()
    {
        return $this->belongsToMany('App\Task');
    }

    public function subtasks()
    {
        return $this->hasMany('App\Subtask');
    } */

    public function hours()
    {
        return $this->hasMany('App\Hour');
    }

    // Get Allocated Users to whom projects has been assigned
    public static function allocatedUsers()
    {
        $allocatedUsers = self::whereHas('roles', function($r){
            $r->whereIn('name', array('teamlead', 'developer'));
        })->whereHas('projects', function($p){
            $p->selectRaw('count(*) AS active')->where('status', 1)->havingRaw('active >= 1');
        })->get();

        return $allocatedUsers;
    }

    // Get Free Users to whom NO project has been assigned
    public static function freeUsers()
    {
        $freeUsers = self::whereHas('roles', function($r){
            $r->whereIn('name', array('teamlead', 'developer'));
        })->whereHas('projects', function($p){
            $p->selectRaw('count(*) AS active')->where('status', 1)->havingRaw('active = 0');
        })->get();

        return $freeUsers;
    }
}
