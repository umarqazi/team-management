<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Hour;


class Project extends Model
{
    protected $fillable = ['name', 'technology', 'description'];

	 public function hours()
    {
        return $this->hasMany('App\Hour');
    }

    public function users()
     {
         return $this->belongsToMany('App\User');
     }

     // Tasks And Components Relation Created By Umar Farooq

    public function tasks()
    {
	    return $this->hasMany('App\Task');
    }

//    public function components()
//    {
//        return $this->belongsToMany('App\Component');
//    }

    public function teamlead()
    {
        return $this->belongsToMany('App\User')->whereHas('roles', function ($query) {
            return $query->where('name', 'teamlead');
        });
    }

    public function developers()
    {
        return $this->belongsToMany('App\User')->whereHas('roles', function ($query) {
            return $query->where('name', 'developer');
        });
    }


}
