<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnCustomerId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_truck_informations', function (Blueprint $table) {
            $table->integer('customer_id')->nullable()->change();
            $table->integer('user_id')->nullable()->after('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_truck_informations', function (Blueprint $table) {
            $table->dropColumn('customer_id');
            $table->dropColumn('user_id');
        });
    }
}
