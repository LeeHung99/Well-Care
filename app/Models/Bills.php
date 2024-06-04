<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    use HasFactory;
    protected $table = 'bills';
    protected $primaryKey = 'id_bill';
    public function User()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
