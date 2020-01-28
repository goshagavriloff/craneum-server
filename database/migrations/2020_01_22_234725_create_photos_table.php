<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('userphoto_id')->unsigned();
            $table->string('name');
            $table->string('url');
            $table->string('ext');//,migration reset 28/01
            $table->string('owner_id');//migration reset 27/01
            $table->boolean('shared')->default(false);//migration reset 26/01
            $table->json('users');
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
        Schema::dropIfExists('photos');
    }
}
