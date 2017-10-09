<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jogo extends Model
{
    protected $table = "jogo";
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    function jogadas() {
        return $this->hasMany('App\Jogada');
    }

    function sorteios() {
        return $this->hasMany('App\Sorteio');
    }
}
