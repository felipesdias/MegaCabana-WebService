<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jogada extends Model
{
    protected $table = "jogada";
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
}
