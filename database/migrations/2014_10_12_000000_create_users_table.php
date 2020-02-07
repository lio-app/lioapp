<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('address')->nullable();
            $table->string('btc_address')->nullable();
            $table->string('eth_address')->nullable();
            $table->string('bch_address')->nullable();
            $table->string('xrp_address')->nullable();
            $table->string('ltc_address')->nullable();
            $table->string('network')->nullable();
            $table->string('password');
            $table->string('device_token')->nullable();
            $table->string('device_id')->nullable();
            $table->enum('device_type',array('android','ios'));
            $table->tinyInteger('verified')->default(0);
            $table->string('email_token')->nullable();
            $table->string('google2fa_secret',255)->nullable();
            $table->tinyInteger('kyc')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
