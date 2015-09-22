<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Dbs\UserType;

class CreateUserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('user_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->softDeletes();
            $table->timestamps();
        });

        UserType::create(['value' => 'Admin']);
        UserType::create(['value' => 'Operative']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_types');
    }
}
