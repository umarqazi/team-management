<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Project;

class Hour extends Model
{
    public function Project()
    {
        return $this->belongsTo('App\Project');
    }
}
