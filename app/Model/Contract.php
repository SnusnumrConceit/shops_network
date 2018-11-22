<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    public $fillable = ['provider_id', 'author_id', 'shop_id', 'created_at', 'deadline'];

//    public $dates = ['timestamp'];

    public function author()
    {
        return $this->hasOne('App\User', 'id', 'author_id');
    }

    public function products()
    {
        return $this->belongsToMany(
            'App\Model\Product',
            'contract_products',
            'contract_id',
            'product_id');
    }

    public function provider()
    {
        return $this->hasOne('App\Model\Provider', 'id', 'provider_id');
    }

    public function shop()
    {
        return $this->hasOne('App\Model\Shop', 'id', 'shop_id');
    }
}
