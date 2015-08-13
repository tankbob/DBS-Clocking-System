<?php

namespace Dbs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use SoftDeletes;

    protected $table = 'jobs';

    protected $fillable = [
		'number',
		'screen_name',
		'address',
		'postcode',
		'contractor',
		'foreman',
        'active'
    ];

    public function LogTimes(){
        return $this->hasMany('Dbs\LogTime', 'job_id');
    }

    public function Users(){
        return $this->belongsToMany('Dbs\User', 'log_times', 'user_id', 'job_id');    
    }
}
