<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Dbs\Job;

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
            $table->string('postcode');
            $table->string('contractor');
            $table->string('foreman');
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        }); 

        Job::create([
            'number' => '0000-0001',
            'screen_name' => 'screen_name1',
            'address' => 'address1',
            'postcode' => 'postcode1',
            'contractor' => 'contractor1',
            'foreman' => 'foreman1'
        ]); 

         Job::create([
            'number' => '0000-0002',
            'screen_name' => 'screen_name2',
            'address' => 'address2',
            'postcode' => 'postcode2',
            'contractor' => 'contractor2',
            'foreman' => 'foreman2'
        ]);

        Job::create([
            'number' => '0000-0003',
            'screen_name' => 'screen_name3',
            'address' => 'address3',
            'postcode' => 'postcode3',
            'contractor' => 'contractor3',
            'foreman' => 'foreman3'
        ]);

        Job::create([
            'number' => '0000-0004',
            'screen_name' => 'screen_name4',
            'address' => 'address4',
            'postcode' => 'postcode4',
            'contractor' => 'contractor3',
            'foreman' => 'foreman4'
        ]);

        Job::create([
            'number' => '0000-0005',
            'screen_name' => 'screen_name5',
            'address' => 'address5',
            'postcode' => 'postcode5',
            'contractor' => 'contractor5',
            'foreman' => 'foreman5'
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
