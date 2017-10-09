<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Sorteio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soteio', function (Blueprint $table) {
            $table->increments('id');
            $table->date('data');
            $table->unsignedInteger('jogo_id');
            $table->tinyInteger('_0');
            $table->tinyInteger('_1');
            $table->tinyInteger('_2');
            $table->tinyInteger('_3');
            $table->tinyInteger('_4');
            $table->tinyInteger('_5');

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
        Schema::dropIfExists('soteio');
    }
}
