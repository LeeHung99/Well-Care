<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objects extends Model
{
    use HasFactory;
    protected $table = 'object';
    protected $primaryKey = 'id_object';
    protected $fillable = ['name', 'hide'];
}
