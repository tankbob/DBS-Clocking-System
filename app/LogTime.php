<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogTime extends Model
{
    protected $table = 'log_times';

    protected $fillable = [
    	'job_id',
    	'user_id',
        'time',
        'date',
        'type'
	];

	public function User(){
		return $this->belongsTo('App\User', 'user_id');
	}

	public function Job(){
		return $this->belongsTo('App\Job', 'job_id');
	}
}
