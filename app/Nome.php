<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nome extends Model
{
    protected $table = "nome";
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
}
