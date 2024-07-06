<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'id_product';
    protected $fillable = [
        'name', 'avatar', 'price', 'in_stock', 'brand', 'hide', 'id_third_category',
        'id_image_product', 'sick', 'object'
    ];
    public function Third_categories()
    {
        return $this->belongsTo(Third_categories::class, 'id_third_category');
    }
    public function Image_product()
    {
        return $this->belongsTo(Third_categories::class, 'id_image_product');
    }
    public function Sick()
    {
        return $this->belongsTo(Sick::class, 'id_sick');
    }
    public function images_product()
    {
        return $this->belongsTo(Image_products::class, 'id_image_product');
    }
}
