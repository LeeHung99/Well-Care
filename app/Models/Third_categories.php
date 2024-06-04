<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Third_categories extends Model
{
    use HasFactory;
    protected $table = 'third_categories';
    protected $primaryKey = 'id_third_category';
    public function Se_categories()
    {
        return $this->belongsTo(Se_categories::class, 'id_se_category');
    }
}
