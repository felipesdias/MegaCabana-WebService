<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Jogada extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jogada', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('jogo_id');
            $table->string('nome');
            $table->integer('numero');
            $table->tinyInteger('_0')->nullable();
            $table->tinyInteger('_1')->nullable();
            $table->tinyInteger('_2')->nullable();
            $table->tinyInteger('_3')->nullable();
            $table->tinyInteger('_4')->nullable();
            $table->tinyInteger('_5')->nullable();
            $table->tinyInteger('_6')->nullable();
            $table->tinyInteger('_7')->nullable();
            $table->tinyInteger('_8')->nullable();
            $table->tinyInteger('_9')->nullable();
            $table->tinyInteger('_10')->nullable();
            $table->tinyInteger('_11')->nullable();
            $table->tinyInteger('_12')->nullable();
            $table->tinyInteger('_13')->nullable();
            $table->tinyInteger('_14')->nullable();
            $table->tinyInteger('_15')->nullable();
            $table->tinyInteger('_16')->nullable();
            $table->tinyInteger('_17')->nullable();
            $table->tinyInteger('_18')->nullable();
            $table->tinyInteger('_19')->nullable();

            $table->foreign('jogo_id')->references('id')->on('jogo')->onDelete('cascade');

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
        Schema::dropIfExists('jogada');
    }
}
