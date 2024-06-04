<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_details extends Model
{
    use HasFactory;
    protected $table = 'product_details';
    protected $primaryKey = 'id_product_detail';
    public function Products()
    {
        return $this->belongsTo(Products::class, 'id_product');
    }
}
