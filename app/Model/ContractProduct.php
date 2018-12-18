<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ContractProduct extends Model
{
    protected $table = 'contract_products';

    protected $fillable = ['contract_id', 'product_id'];
}
