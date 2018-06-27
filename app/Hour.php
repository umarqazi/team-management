<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Project;
use App\User;

class Hour extends Model
{
	protected $fillable = ['consumed_hours', 'estimated_hours','internal_hours', 'details', 'task_id', 'subtask_id', 'created_at'];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function subtask()
    {
        return $this->belongsTo('App\Subtask');
    }
}