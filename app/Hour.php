<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Project;
use App\User;

class Hour extends Model
{
	protected $fillable = ['actual_hours', 'productive_hours', 'details', 'created_at'];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}