<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportDriverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_driver', function (Blueprint $table) {
            $table->id("report_driver_id");
            $table->integer("customer_id")->nullable();
            $table->integer("driver_id")->nullable();
            $table->string("title")->nullable();
            $table->string("content")->nullable();
            $table->boolean("status")->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('report_driver');
    }
}
