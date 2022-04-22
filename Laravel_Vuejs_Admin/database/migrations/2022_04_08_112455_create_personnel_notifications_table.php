<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonnelNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personnel_notifications', function (Blueprint $table) {
            $table->id('personnel_notification_id');
            $table->string('title');
            $table->string('notification_avatar')->nullable();
            $table->string('link')->nullable();
            $table->boolean('notification_status')->default(0);
            $table->bigInteger('user_id')->nullable();
            $table->timestamp('user_read_at')->nullable();
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
        Schema::dropIfExists('personnel_notifications');
    }
}
