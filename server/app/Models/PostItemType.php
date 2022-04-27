<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostItemType extends Model
{
    use HasFactory;
    protected $table = 'post_item_type';
    protected $primaryKey = 'post_item_type_id';
    protected $fillable = [
        'post_id',
        'item_type_id',
    ];
}
