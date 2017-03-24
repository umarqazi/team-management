<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Hour;

class Project extends Model
{
	protected $fillable = ['name', 'technology', 'teamlead', 'developer', 'description'];

	 public function hours()
    {
        return $this->hasMany('App\Hour');
    }

    // public function accumulative_hours()
    // {
    // 	return $this->hasMany('App\Hour')->selectRaw('sum(productive_hours) as productive_hours');
    // }


}
