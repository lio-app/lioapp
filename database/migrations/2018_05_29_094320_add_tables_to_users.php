<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTablesToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('wallet')->nullable();
            $table->string('g2f_temp',255)->nullable();
            $table->string('referred_by')->nullable();
            $table->string('app_pin')->nullable();
            $table->string('app_pin_status')->nullable();
            $table->string('send_email_status')->nullable();
            $table->string('receive_email_status')->nullable();
            $table->string('ip')->nullable();
            $table->string('coin')->nullable();
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
            $table->dropColumn('wallet');
            $table->dropColumn('g2f_temp');
            $table->dropColumn('referred_by');
            $table->dropColumn('app_pin');
            $table->dropColumn('app_pin_status');
            $table->dropColumn('send_email_status');
            $table->dropColumn('receive_email_status');
            $table->dropColumn('ip');
            $table->dropColumn('coin');
        });
    }
}
