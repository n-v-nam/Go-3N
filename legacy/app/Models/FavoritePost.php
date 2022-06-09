<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FavoritePost extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'favorite_post';
    protected $primaryKey = 'favorite_post_id';
    protected $fillable = [
        'customer_id',
        'post_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}
