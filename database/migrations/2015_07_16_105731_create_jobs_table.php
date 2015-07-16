<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Job;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number');
            $table->string('screen_name');
            $table->string('address');
            $table->string('post_code');
            $table->string('contractor');
            $table->string('foreman');
            $table->softDeletes();
            $table->timestamps();
        }); 

        Job::create([
            'number' => '0000-0001',
            'screen_name' => 'screen_name1',
            'address' => 'address1',
            'post_code' => 'post_code1',
            'contractor' => 'contractor1',
            'foreman' => 'foreman1'
        ]); 

         Job::create([
            'number' => '0000-0002',
            'screen_name' => 'screen_name2',
            'address' => 'address2',
            'post_code' => 'post_code2',
            'contractor' => 'contractor2',
            'foreman' => 'foreman2'
        ]);

        Job::create([
            'number' => '0000-0003',
            'screen_name' => 'screen_name3',
            'address' => 'address3',
            'post_code' => 'post_code3',
            'contractor' => 'contractor3',
            'foreman' => 'foreman3'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jobs');
    }
}
