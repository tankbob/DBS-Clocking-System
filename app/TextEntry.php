<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TextEntry extends Model
{
    protected $table = 'text_entries';

    protected $fillable = ['key', 'value'];
}
