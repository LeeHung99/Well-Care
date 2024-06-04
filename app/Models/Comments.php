<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;
    protected $table = 'comments';
    protected $primaryKey = 'id_comment';
    public function User()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function Product()
    {
        return $this->belongsTo(Products::class, 'id_product');
    }
}
