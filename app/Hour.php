<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Project;

class Hour extends Model
{
	protected $fillable = ['actual_hours', 'productive_hours', 'details', 'created_at'];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }
}
