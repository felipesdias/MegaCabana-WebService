<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sorteio extends Model
{
    protected $table = "soteio";
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
}
