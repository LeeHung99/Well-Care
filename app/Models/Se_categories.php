<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Se_categories extends Model
{
    use HasFactory;
    protected $table = 'se_categories';
    protected $primaryKey = 'id_se_category';

    public function Category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    public function SeCategory($id)
    {
        $seCate = DB::table('Se_categories')
        ->where('id_category',$id)
        ->get();
        return $seCate;
    }
}
