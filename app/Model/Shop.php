<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    public $fillable = ['name', 'address', 'bank_account', 'contact_phone'];
    public $timestamps=  false;
}
