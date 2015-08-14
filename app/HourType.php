<?php

namespace Dbs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HourType extends Model
{
	use SoftDeletes;

    protected $table = 'hour_types';

    protected $fillable = ['value'];
}
