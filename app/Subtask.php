<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    // Relations Established By Umar Farooq
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function hours()
    {
        return $this->hasMany('App\Hour');
    }
}
