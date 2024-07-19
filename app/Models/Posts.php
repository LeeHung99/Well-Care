<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $primaryKey = 'id_post';
    protected $filable = [
        'title', 'short_des', 'description', 'avatar'
    ];
    public function User()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function Article_categories()
    {
        return $this->belongsTo(Article_categories::class, 'id_article_category');
    }
}
