<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmIsReviewInOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_informations', function (Blueprint $table) {
            $table->tinyInteger("is_reviewed")->after("post_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_informations', function (Blueprint $table) {
            $table->dropColumn("is_reviewed");
        });
    }
}
