<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Project;

class Hour extends Model
{
    public function project()
    {
        return $this->belongsTo('App\Project');
    }
}
