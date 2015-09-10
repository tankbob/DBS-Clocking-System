<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveMissedHours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        {
            Schema::drop('missed_hours');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('missed_hours', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('time');
            $table->date('date');
            $table->string('hour_type');
            $table->softDeletes();
            $table->timestamps();
        });
    }
}
