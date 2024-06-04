<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill_details extends Model
{
    use HasFactory;
    protected $table = 'bill_details';
    protected $primaryKey = 'id_bill_details';
    public function Bills()
    {
        return $this->belongsTo(Bills::class, 'id_bill');
    }
    public function Products()
    {
        return $this->belongTo(Products::class, 'id_product');
    }
}
