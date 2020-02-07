<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddXrpPvtToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('x_remember_flag_port')->nullable()->after('xrp_address');
            $table->string('x_remember_flag_star')->nullable()->after('x_remember_flag_port');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('x_remember_flag_port');
            $table->dropColumn('x_remember_flag_star');
        }); 
    }
}
