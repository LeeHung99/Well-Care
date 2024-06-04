<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image_products extends Model
{
    use HasFactory;
    protected $table = 'image_products';
    protected $primaryKey = 'id_image_product';
    public function Products()
    {
        return $this->belongsTo(Products::class, 'id_product');
    }
}
