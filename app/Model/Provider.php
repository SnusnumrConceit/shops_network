<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = ['name', 'legal_address', 'bank_account', 'contact_phone'];
    public $timestamps = false;

    public function products()
    {
        return $this->belongsToMany(
            '\App\Model\Product',
            'provider_products',
            'provider_id',
            'product_id');
    }
}
