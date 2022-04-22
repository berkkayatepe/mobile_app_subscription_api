<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->integer('app_id');
            $table->string('client_token');
            $table->string('receipt');
            $table->dateTime('subscription_date');
            $table->dateTime('expiry_date');
            $table->enum('subscription_status', ['started', 'renewed', 'canceled']);
            $table->integer('expiry_date_check')->default(0);
            $table->timestamps();
            $table->foreign('device_id')->references('id')->on('devices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription');
    }
}
