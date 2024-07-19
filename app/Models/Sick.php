<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sick extends Model
{
    use HasFactory;
    protected $table = 'sick';
    protected $primaryKey = 'id_sick';
    protected $fillable = [
        'name', 'symptom', 'description', 'hide', 'created_at', 'update_at'
    ];
}
