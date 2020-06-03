<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('link');
            $table->string('brand');
            $table->string('code');
            $table->integer('stock');
            $table->float('price');
            $table->integer('currency_id');
            $table->float('discount');
            $table->float('shipping');
            $table->integer('max_quantity');
            $table->integer('min_quantity');
            $table->integer('category_id');
            $table->longText('description');
            $table->longText('short_description');
            $table->integer('user_id');
            $table->string('image',15);
            $table->boolean('status');
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
        Schema::drop('products');
    }
}
