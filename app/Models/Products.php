<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'id_product';
    public function Third_categories()
    {
        return $this->belongsTo(Third_categories::class, 'id_third_category');
    }
    public function Image_product()
    {
        return $this->belongsTo(Third_categories::class, 'id_image_product');
    }
}
