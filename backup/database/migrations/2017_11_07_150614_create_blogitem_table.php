<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogitem', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('blogcategory_id');
            $table->string('title');
            $table->string('link');
            $table->string('date');
            $table->string('image',15);
            $table->longText('text');
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
        Schema::drop('blogitem');
    }
}
