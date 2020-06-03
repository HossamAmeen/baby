<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponsiveTxtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsive_txt', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_title');
            $table->string('message_text');
            $table->string('client_text');
            $table->string('number_title');
            $table->integer('num1');
            $table->string('num1_value');
            $table->integer('num2');
            $table->string('num2_value');
            $table->integer('num3');
            $table->string('num3_value');
            $table->integer('num4');
            $table->string('num4_value');
            $table->string('company_txt');
            $table->string('instructor_txt1');
            $table->string('instructor_txt2');
            $table->string('search_title1');
            $table->string('search_txt');
            $table->string('join_title');
            $table->string('join_txt');
            $table->string('join_img',15);
            $table->string('contact_txt');
            $table->string('newsletter_txt');
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
        Schema::drop('responsive_txt');
    }
}
