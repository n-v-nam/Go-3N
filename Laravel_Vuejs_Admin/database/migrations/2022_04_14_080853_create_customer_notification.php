<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_notification', function (Blueprint $table) {
            $table->id('customer_notification_id');
            $table->string('title');
            $table->string('notification_avatar')->nullable();
            $table->string('link')->nullable();
            $table->boolean('notification_status')->default(0);
            $table->bigInteger('customer_id')->nullable();
            $table->timestamp('customer_read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_notification');
    }
}
