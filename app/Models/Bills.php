<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    use HasFactory;
    protected $table = 'bills';
    protected $primaryKey = 'id_bill';
    protected $fillable = ['id_bill', 'id_user', 'transport_status', 'payment_status', 'address', 'voucher'];
    public function User()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function details()
    {
        return $this->hasMany(Bill_details::class, 'id_bill');
    }
}
