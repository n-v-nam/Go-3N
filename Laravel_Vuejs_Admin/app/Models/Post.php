<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'post';
    protected $primaryKey = 'post_id';
    protected $fillable = [
        'truck_id',
        'title',
        'content',
        'from_city_id',
        'to_city_id',
        'post_type',
        'weight_product',
        'lowest_price',
        'highest_price',
        'start_date',
        'end_date',
        'is_approve',
        'is_approve_at',
        'user_id',
        'status',
    ];
}
