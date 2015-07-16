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
        Schema::create('log_times', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_id');
            $table->integer('user_id');
            $table->integer('time');
            $table->date('date');
            $table->integer('type');
            $table->softDeletes();
            $table->timestamps();
        });

        for($i = 1; $i <= 3; $i++){
            for($j = $i; $j <= 3; $j++){
                LogTime::create([
                    'job_id' => $i,
                    'user_id' => $j,
                    'time' => $i+$j,
                    'date' => date('Y-m-d'),
                    'type' => 1
                ]);
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
