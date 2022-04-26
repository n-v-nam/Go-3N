<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRenameTableCountProductBookTruckInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_truck_informations', function (Blueprint $table) {
            $table->renameColumn('count_product', 'count');
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
            $table->renameColumn('count', 'count_product');
        });
    }
}
