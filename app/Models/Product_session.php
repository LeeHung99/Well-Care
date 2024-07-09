<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_session extends Model
{
    use HasFactory;
    protected $table = 'product_session';
    protected $primaryKey = 'id_product_session';
    protected $fillable = [
        'quantity', 'price', 'phone_number', 'payment_status', 'addres', 'voucher', 'ghichu', 'total_amount', 'id_product', 'address', 'temp_order_id'
    ];
}
