<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article_categories extends Model
{
    use HasFactory;
    protected $table = 'article_categories';
    protected $primaryKey = 'id_article_category';
}
