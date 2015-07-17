<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HourType extends Model
{
    protected $table = 'hour_types';

    protected $fillable = ['value'];
}
