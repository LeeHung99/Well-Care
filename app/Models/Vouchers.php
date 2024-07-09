<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vouchers extends Model
{
    use HasFactory;
    protected $table = 'Vouchers';
    protected $primaryKey = 'id_voucher';
    protected $fillable = [
        'name', 'code', 'number', 'status', 'count_voucher'
    ];
}
