<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuration', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('logo',15);
            $table->string('phone');
            $table->string('sales_phone');
            $table->string('fax');
            $table->string('email');
            $table->string('address');
            $table->string('facebook');
            $table->string('twitter');
            $table->string('linkedin');
            $table->string('googleplus');
            $table->string('youtube');
            $table->string('instgram');
            $table->longText('copyrights');
            $table->string('contactus');
            $table->string('googlemap');
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
        Schema::drop('configuration');
    }
}
