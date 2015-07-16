<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    protected $table = 'jobs';

    protected $fillable = [
		'number',
		'screen_name',
		'address',
		'post_code',
		'contractor',
		'foreman'
    ];

    public function LogTimes(){
        return $this->hasMany('App\LogTime', 'job_id');
    }

    public function Users(){
        return $this->belongsToMany('App\User', 'log_times', 'user_id', 'job_id');    
    }
}
