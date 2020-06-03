<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_session', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('session_number');
            $table->integer('count');
            $table->float('optionprice');
            $table->string('optionradio');
            $table->string('optioncheck');
            $table->integer('couponid');
            $table->float('coupon_price');
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
        Schema::drop('cart_session');
    }
}
