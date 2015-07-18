<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email',    
        'password',
        'telephone',
        'standart_salary',
        'weekends_5_salary',
        'weekends_9_salary',
        'holiday_salary',
        'overtime_salary',
        'user_type_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function LogTimes(){
        return $this->hasMany('App\LogTime', 'user_id');
    }

    public function Jobs(){
        return $this->belongsToMany('App\Job', 'log_times', 'job_id', 'user_id');    
    }

    public function UserType(){
        return $this->belongsTo('App\UserType', 'user_type_id');
    }
}
