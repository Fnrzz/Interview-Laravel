<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //
    protected $primaryKey = 'id_sales';
    protected $fillable = [
        'sales_refrence',
        'sales_date',
        'product_code',
        'quantity',
        'price',
        'subtotal'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }
}
