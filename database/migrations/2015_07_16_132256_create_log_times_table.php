<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\LogTime;

class CreateLogTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('log_times'))
{
  Schema::drop('log_times');
}

        Schema::create('log_times', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_id');
            $table->integer('user_id');
            $table->integer('time');
            $table->integer('overtime');
            $table->date('date');
            $table->integer('hour_type_id');
            $table->softDeletes();
            $table->timestamps();
        });

        for($user_id = 1; $user_id <= 4; $user_id ++){
            for($job_id = 1; $job_id <= 5; $job_id ++){
                for($date = date('Y-m-d'); $date >= date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-42, date("Y")));  $date = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($date)), date('d', strtotime($date))-1, date('Y', strtotime($date))))){
                    if(rand(0, 10) > 2){
                        LogTime::create([
                            'job_id' => $job_id,
                            'user_id' => $user_id,
                            'time' => rand(1, 8),
                            'overtime' => '0',
                            'hour_type_id' => 1,
                            'date' => $date
                        ]); 
                    }elseif(rand(0, 10) > 8){
                        LogTime::create([
                            'job_id' => $job_id,
                            'user_id' => $user_id,
                            'time' => '8',
                            'overtime' => rand(1, 2),
                            'hour_type_id' => 1,
                            'date' => $date
                        ]); 
                    }                                        
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('log_times');
    }
}
