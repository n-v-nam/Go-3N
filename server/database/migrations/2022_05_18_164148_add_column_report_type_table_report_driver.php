<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnReportTypeTableReportDriver extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_driver', function (Blueprint $table) {
            $table->tinyInteger("report_type")->after("driver_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_driver', function (Blueprint $table) {
            $table->dropColumn("report_type");
        });
    }
}
