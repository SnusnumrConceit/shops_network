<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'cost', 'manufacture_date'];
    public $timestamps = false;
}
