<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('number_of_items_sold')->default(0);
            $table->integer('number_of_orders')->default(0);
            $table->decimal('average_net_sales_amount')->default(0);
            $table->decimal('coupon_amount')->default(0.00);
            $table->decimal('shipping_amount')->default(0.00);
            $table->decimal('gross_sales_amount')->default(0.00);
            $table->decimal('net_sales_amount');
            $table->integer('refund_amount')->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revenues');
    }
}
