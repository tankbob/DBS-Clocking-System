<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Dbs\HourType;

class CreateHourTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hour_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->softDeletes();
            $table->timestamps();
        });

        HourType::create(['value' => 'Normal']);
        HourType::create(['value' => 'Holiday']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hour_types');
    }
}
