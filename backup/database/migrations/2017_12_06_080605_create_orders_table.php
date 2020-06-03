<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number');
            $table->integer('user_id');
            $table->integer('product_id');
            $table->string('coupon_code');
            $table->integer('coupon_id');
            $table->float('coupon_price');
            $table->integer('quantity');
            $table->float('one_price');
            $table->float('option_price');
            $table->float('shipping_price');
            $table->float('total_price');
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
        Schema::drop('orders');
    }
}
