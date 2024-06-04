<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image_banners extends Model
{
    use HasFactory;
    protected $table = 'image_banners';
    protected $primaryKey = 'id_image_banner';
}
