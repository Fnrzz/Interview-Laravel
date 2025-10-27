<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $primaryKey = 'id_product';
    protected $fillable = [
        'product_code',
        'product_name',
        'price',
        'stock'
    ];
}
