<?php

namespace Dbs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogTime extends Model
{
	use SoftDeletes;

    protected $table = 'log_times';

    protected $fillable = [
    	'job_id',
    	'user_id',
        'time',
        'date',
        'hour_type_id',
        'overtime'
	];

	public function User(){
		return $this->belongsTo('Dbs\User', 'user_id');
	}

	public function Job(){
		return $this->belongsTo('Dbs\Job', 'job_id');
	}

	public function HourType(){
		return $this->belongsTo('Dbs\HourType', 'hour_type_id');
	}
}
