<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('telephone');
            $table->integer('type');
            $table->string('standart_salary');
            $table->string('weekends_5_salary');
            $table->string('weekends_9_salary');
            $table->string('holiday_salary');
            $table->string('overtime_salary');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        User::create([
            'name' => 'aaa aaa',
            'email' => 'a@a.a',
            'password' => \Hash::make('123456'),
            'telephone' => '0123 456 789',
            'type' => '1'
        ]);

        User::create([
            'name' => 'bbb bbb',
            'email' => 'b@b.b',
            'password' => \Hash::make('123456'),
            'telephone' => '0123 456 789',
            'type' => '1'
        ]);

        User::create([
            'name' => 'ccc ccc',
            'email' => 'c@c.c',
            'password' => \Hash::make('123456'),
            'telephone' => '0123 456 789',
            'type' => '1'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
