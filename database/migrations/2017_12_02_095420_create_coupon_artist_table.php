<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponArtistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_artist', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('artist_id')->unsigned();
            $table->foreign('artist_id')
                   ->references('id')
                   ->on('makeup_artist')
                   ->onDelete('cascade');
            $table->integer('coupon_id')->unsigned();
            $table->foreign('coupon_id')
                   ->references('id')
                   ->on('coupons')
                   ->onDelete('cascade');   
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
        Schema::drop('coupon_artist');
    }
}
