<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameFieldInLogHours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_times', function (Blueprint $table) {
            $table->renameColumn('aproved', 'approved');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_times', function (Blueprint $table) {
            $table->renameColumn('approved', 'aproved');
        });
    }
}
