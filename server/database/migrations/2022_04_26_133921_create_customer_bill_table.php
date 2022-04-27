<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_bill', function (Blueprint $table) {
            $table->id("customer_bill_id");
            $table->integer("customer_id")->nullable();
            $table->string("customer_bill_code")->nullable();
            $table->bigInteger("amount")->nullable();
            $table->string("bank_code");
            $table->string("content")->nullable();
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
        Schema::dropIfExists('customer_bill');
    }
}
