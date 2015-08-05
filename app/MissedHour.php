<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MissedHour extends Model
{
    use SoftDeletes;

    protected $table = 'missed_hours';

    protected $fillable = [
    	'user_id',
        'time',
        'date',
        'hour_type',
	];

	public function User(){
		return $this->belongsTo('App\User', 'user_id');
	}
}
