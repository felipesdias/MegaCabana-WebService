<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jogo extends Model
{
    protected $table = "jogo";
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    function jogadas() {
        return $this->hasMany('App\Jogada')->orderBy('nome');
    }

    function sorteios() {
        return $this->hasMany('App\Sorteio')->orderBy('data', 'asc');
    }
}
