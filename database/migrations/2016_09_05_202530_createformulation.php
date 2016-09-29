<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Createformulation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->string('Enunciado');
            $table->string('respuesta');
            $table->integer('skill_id');            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('formulation');
    }
}
